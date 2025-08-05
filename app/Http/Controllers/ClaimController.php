<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimDocument;
use App\Models\DocumentType;
use App\Models\Insurance;
use App\Models\InsuranceDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client as TwilioClient;
use GuzzleHttp\Client as GuzzleClient; 
use Log;
// use Mpdf\Mpdf;
use setasign\Fpdi\Fpdi;
use App\Models\Part;
use App\Services\InsuranceDetailExtractor;
use App\Services\DrivingLicenseDetailExtractor;
use App\Models\ClaimLog;
use App\Models\VehicleRegistration;
use App\Models\InsuranceDetail;
use App\Models\DlDetail;
use App\Models\VehicleDepreciation;
use App\Models\ProfessionalFee;
use Carbon\Carbon;
use App\Models\Gst;
use Illuminate\Support\Facades\Config;
use App\Exports\ClaimReportExport;
use App\Exports\ClaimReportPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Illuminate\Support\Facades\Storage;
// use Mpdf\Mpdf as MpdfLibrary;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf as PdfWriter;
use PhpOffice\PhpSpreadsheet\Writer\Html as HtmlWriter;
use Mpdf\Mpdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\Services\SMSCountryService;
use Illuminate\Support\Facades\Http;
use App\Helper\ShortTokenHelper;
use Illuminate\Support\Str;
use App\Models\ShortUrl;
use Intervention\Image\ImageManagerStatic as Image;
// use setasign\Fpdf\Fpdf\Fpdf;
use FPDF;
use App\Models\State;
use App\Models\City;
use App\Models\InsuranceCompany;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Session;

class ClaimController extends Controller
{
    protected $client;
    protected $ocrApiKey;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
        $this->ocrApiKey = Config::get('services.ocr.api_key');
    }

    public function index(Request $request)
    {
        if (\Auth::user()->can('manage claim')) {

            $user = \Auth::user();
            // Base query depending on user type
            $query = Claim::where('parent_id', parentId());

            if ($user->type === 'Operator') {
                $query->where('user_id', $user->id);
            }

            if ($user->type === 'workshop') {
                $query->where('user_id', $user->id);
            }
            
            // Apply date range filters if provided
            if ($request->filled('date_filter') && !$request->filled('start_date') && !$request->filled('end_date')) {
                switch ($request->date_filter) {
                    case 'today':
                        $query->whereDate('created_at', Carbon::today());
                        break;
                    case 'this_week':
                        $query->whereBetween('created_at', [
                            Carbon::now()->startOfWeek(), 
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                    case 'this_month':
                        $query->whereBetween('created_at', [
                            Carbon::now()->startOfMonth(), 
                            Carbon::now()->endOfMonth()
                        ]);
                        break;
                    case 'this_year':
                        $query->whereYear('created_at', Carbon::now()->year);
                        break;
                    default:
                        \Log::warning('Unknown filter: ' . $request->date_filter);
                        break;
                }
            }
            elseif ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            // Vehicle type filter from request
            $vehicleType = $request->input('vehicle_type'); // dropdown name="vehicle_type"

            $insuranceDetailQuery = InsuranceDetail::query();

            if ($vehicleType == '2') {
                $insuranceDetailQuery->where('seating_capacity', 2)
                                    ->whereNotNull('seating_capacity')
                                    ->where('seating_capacity', '!=', 0);
            } elseif ($vehicleType == '3') {
                $insuranceDetailQuery->where('seating_capacity', 3)
                                    ->whereNotNull('seating_capacity')
                                    ->where('seating_capacity', '!=', 0);
            } elseif ($vehicleType == '4') {
                $insuranceDetailQuery->whereIn('seating_capacity', [4, 5])
                                    ->whereNotNull('seating_capacity')
                                    ->where('seating_capacity', '!=', 0);
            } elseif ($vehicleType == 'more') {
                $insuranceDetailQuery->where('seating_capacity', '>', 5)
                                    ->whereNotNull('seating_capacity')
                                    ->where('seating_capacity', '!=', 0);
            }

            // Get filtered claim IDs from insurance details
            if ($vehicleType) {
                $filteredClaimIds = $insuranceDetailQuery->pluck('claim_id');
                $query->whereIn('id', $filteredClaimIds);
            }

            // GET FINAL CLAIMS
            $claims = $query->orderBy('created_at', 'desc')->get();

            

            // GET INSURANCE DETAILS ONLY FOR THESE CLAIMS
            // $insuranceDetail = InsuranceDetail::whereIn('claim_id', $claims->pluck('id'))->get();

            $insuranceDetail = InsuranceDetail::whereIn('claim_id', $claims->pluck('id'))->get()->keyBy('claim_id');

            // dd($insuranceDetail);
            $states = State::pluck('name', 'id');
            //  dd($states);
            $usersType = User::where('type', 'manager')->get();

            //FEES BILL DATA
            $feesBillData = ProfessionalFee::whereIn('claim_id', $claims->pluck('id'))->get()->keyBy('claim_id');
            
            return view('claim.index', compact('claims','feesBillData','insuranceDetail','states','usersType'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create claim')) {

            $customer = User::where('parent_id', parentId())->where('type', 'customer')->get()->pluck('name', 'id');
            $customer->prepend(__('Select Customer'), '');

            $status = Claim::$status;
            $insurance_companies = InsuranceCompany::pluck('name', 'id');
            $states = State::pluck('name', 'id');

            return view('claim.create', compact('customer', 'status','states','insurance_companies'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->pluck('name', 'id');
        return response()->json($cities);
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create claim')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'loss_date' => 'required|date',
                    'policy_number' => 'required|string|max:255',
                    'mobile' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'workshop_email' => 'required|email|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $claim = new Claim();
            $claim->claim_id = $request->claim_id;
            $claim->date = $request->date;
            $claim->status = $request->status;
            $claim->notes = $request->notes;
            $claim->parent_id = parentId();
            $claim->loss_date = $request->loss_date;
            $claim->location = $request->location;
            $claim->place_of_survey = $request->place_of_survey;
            $claim->claim_amount = $request->claim_amount;
            $claim->policy_number = $request->policy_number;
            $claim->mobile = $request->mobile;
            $claim->email = $request->email;
            $claim->workshop_email = $request->workshop_email;
            $claim->workshop_name = $request->workshop_name;
            $claim->workshop_address = $request->workshop_address;
            $claim->workshop_mobile_number = $request->workshop_mobile_number;
            $claim->ensurance_email = $request->ensurance_email;
            $claim->state_id = $request->state_id;
            $claim->city_id = $request->city_id;
            $claim->insurance_company_id = $request->insurance_company_id;

            $claim->save();
            $this->logClaimAction(
                $claim,
                'create',
                'Claim created',
                null,
                $claim->toArray()
            );
    
        try {
            $uploadLink = route('claim.upload', ['id' => Crypt::encrypt($claim->id)]);
            $uploadLink = $this->shortenUrl7($uploadLink); // Use your own domain here
            $mail = new PHPMailer(true);

            //Server settings
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST'); // e.g., smtp.gmail.com
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME'); // Gmail username
            $mail->Password = env('MAIL_PASSWORD'); // Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = env('MAIL_PORT', 465); 

            //Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->email);  // Add recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Upload Your Claim Documents';
            $mail->Body = "Please upload your documents using the following link: <a href='$uploadLink'>$uploadLink</a>";

            $mail->send();

            $claim->status = 'link_shared';
            $claim->save();
           } catch (\Exception $e) {
           
           }
            $authKey = env('SMSCOUNTRY_AUTHKEY');
            $authToken = env('SMSCOUNTRY_AUTHTOKEN');
            $senderId = env('SMSCOUNTRY_SENDERID');

            $auth = base64_encode("$authKey:$authToken");

            // Define both numbers
            $numbers = [];

            // Normalize and add claim user mobile
            $mobile = $request->mobile;
            if (substr($mobile, 0, 3) === '+91') {
                $mobile = substr($mobile, 3);
            } elseif (substr($mobile, 0, 1) === '0') {
                $mobile = substr($mobile, 1);
            }
            $numbers[] = '91' . $mobile;

            // Normalize and add workshop mobile number
            $workshopMobile = $request->workshop_mobile_number;
            if (substr($workshopMobile, 0, 3) === '+91') {
                $workshopMobile = substr($workshopMobile, 3);
            } elseif (substr($workshopMobile, 0, 1) === '0') {
                $workshopMobile = substr($workshopMobile, 1);
            }
            $numbers[] = '91' . $workshopMobile;

            // Send SMS to each number
            foreach ($numbers as $number) {
                $data = [
                    "Text" => "Dear ABC, To process your Car insurance claim -Claim No: $request->claim_id, please upload the required documents at: $uploadLink For help, call 080-62965696. Regards SafetyFirst",
                    "Number" => $number,
                    "SenderId" => $senderId,
                    "TemplateId" => "1707174703017862364",
                    "Is_Unicode" => false
                ];

                $response = Http::withHeaders([
                    'Authorization' => "Basic $auth",
                    'Content-Type' => 'application/json'
                ])->post("https://restapi.smscountry.com/v0.1/Accounts/$authKey/SMSes", $data);

                $responseData = $response->json();

                if (!empty($responseData['Success'])) {
                    \Log::info("SMS successfully queued", [
                        'uuid' => $responseData['MessageUUID'],
                        'mobile' => $number
                    ]);
                } else {
                    \Log::error('SMSCountry failed', ['mobile' => $number, 'response' => $response->body()]);
                }
            }
        
        return redirect()->route('claim.show', Crypt::encrypt($claim->id))->with('success', __('Claim successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    private function shortenUrl7($longUrl)
    {
        $existing = ShortUrl::where('original_url', $longUrl)->first();

        if ($existing) {
            return route('short.redirect') . '?' . $existing->code;
        }

        do {
            $code = Str::random(6);
        } while (ShortUrl::where('code', $code)->exists());

        ShortUrl::create([
            'code' => $code,
            'original_url' => $longUrl
        ]);

        return route('short.redirect') . '?' . $code;
    }

    private function shortenUrl6($longUrl)
    {
        // Encrypt and base64 encode the full URL
        $encrypted = Crypt::encryptString($longUrl);
        $encoded = rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');

        // Fake short code part just for display structure
        $code1 = strtoupper(Str::random(6));   // e.g. SFTYFT
        $code2 = $encoded;                     // carries the encrypted data

        // Return the full short-style URL
        return 'https://vm.ltd/' . $code1 . '/' . $code2;
    }

    // public function sendSMS(SMSCountryService $smsCountry)
    // {
    //     $phone = '9198XXXXXX'; // Must include country code
    //     $message = 'Your OTP code is 123456';

    //     $result = $smsCountry->sendSMS($phone, $message);

    //     return response()->json($result);
    // }

    // private function shortenUrl1($claimId)
    // {
    //     $encrypted = Crypt::encryptString($claimId);
    //     $encoded = rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    //     return url('/claim/u/' . $encoded);
    // }

    private function shortenUrl4($longUrl)
    {
        $response = Http::withHeaders([
            'apikey' => env('REBRANDLY_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.rebrandly.com/v1/links', [
            'destination' => $longUrl
        ]);

        if ($response->successful()) {
            return $response->json()['shortUrl'];
        }

        return $longUrl;
    }


    private function shortenUrl1($longUrl)
    {
        $response = Http::withToken(env('BITLY_TOKEN'))
            ->post('https://api-ssl.bitly.com/v4/shorten', [
                'long_url' => $longUrl
            ]);

        if ($response->successful()) {
            return $response->json()['link'];
        }

        return $longUrl; // fallback
    }


    private function shortenUrl2($claimId)
    {
        // base62 encode
        return url('/claim/u/' . $this->base62_encode($claimId));
    }

    private function base62_encode($num)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($chars);
        $result = '';
        while ($num > 0) {
            $result = $chars[$num % $base] . $result;
            $num = floor($num / $base);
        }
        return $result ?: '0';
    }

    private function base62_decode($str)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($chars);
        $len = strlen($str);
        $num = 0;
        for ($i = 0; $i < $len; $i++) {
            $num = $num * $base + strpos($chars, $str[$i]);
        }
        return $num;
    }

    public function assignClaims(Request $request)
    {
        $claimIds = json_decode($request->input('claim_ids'), true);
        
        if (\Auth::user()->can('manage claim')) {
            $validatedData = $request->merge(['claim_ids' => $claimIds])->validate([
                'user_id' => 'required|exists:users,id',
                'claim_ids' => 'required|array',
                'claim_ids.*' => 'exists:claims,id',
            ]);
    
            $userId = $validatedData['user_id'];
            
            // Update claims to assign them to the selected user
            Claim::whereIn('id', $claimIds)->update(['user_id' => $userId]);
    
            return redirect()->route('claim.index')->with('success', __('Claims successfully assigned.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    

    private function shortenUrl($longUrl)
    {
        $client = new GuzzleClient();
        $response = $client->get('https://tinyurl.com/api-create.php', [
            'query' => ['url' => $longUrl]
        ]);

        if ($response->getStatusCode() == 200) {
            return $response->getBody()->getContents();
        }

        return $longUrl;
    }

    public function show($ids)
    {
        if (\Auth::user()->can('show claim')) {
            $id = Crypt::decrypt($ids);
            $claim = Claim::find($id);
            $ocrResults = json_decode($claim->ocr_results, true);
            $numberPlate = json_decode($claim->number_plate_file,true);
            $vehicleNumber = $claim->vehicle_number;
            // dd($numberPlateNumber);
            $cleanedOcrResults = null;
    
            if ($ocrResults !== null) {
                $cleanedOcrResults = $this->cleanOcrResults($ocrResults);
            }
    
            // Process Insurance details using the extractor
            //commented by tanuja 24-07-25
            /*$insurance = $claim->insurances;
            if($claim->ocr_results != null) {
                $insuranceExtractor = new InsuranceDetailExtractor($claim->ocr_results);
                $insuranceDetails = $insuranceExtractor->extract(); 
            }
            
            // Save Insurance details if not already saved
            $existingInsuranceDetail = InsuranceDetail::where('claim_id', $claim->id)->first();
            if (!$existingInsuranceDetail) {
                $insuranceDetailData = [
                    'policy_number' => $insuranceDetails['policy_number'] ?? 'N/A',
                    'previous_policy_number' => $insuranceDetails['previous_policy_number'] ?? '',
                    'insured_name' => $insuranceDetails['insured_name'] ?? 'N/A',
                    'insured_address' => $insuranceDetails['insured_address'] ?? '',
                    'insured_declared_value' => $insuranceDetails['insured_declared_value'] ?? '0',
                    'issuing_office_address_code' => $insuranceDetails['issuing_office_address_code'] ?? '',
                    'issuing_office_address' => $insuranceDetails['issuing_office_address'] ?? '',
                    'occupation' => $insuranceDetails['occupation'] ?? '',
                    'mobile' => $insuranceDetails['mobile'] ?? '',
                    'vehicle' => $insuranceDetails['vehicle'] ?? 'N/A',
                    'engine_no' => $insuranceDetails['engine_no'] ?? '',
                    'chassis_no' => $insuranceDetails['chassis_no'] ?? '',
                    'make' => $insuranceDetails['make'] ?? '',
                    'model' => $insuranceDetails['model'] ?? '',
                    'year_of_manufacture' => $insuranceDetails['year_of_manufacture'] ?? '',
                    'cubic_capacity' => $insuranceDetails['cubic_capacity'] ?? 0,
                    'seating_capacity' => $insuranceDetails['seating_capacity'] ?? 0,
                    'no_claim_bonus_percentage' => $insuranceDetails['no_claim_bonus_percentage'] ?? 0,
                    'nil_depreciation' => $insuranceDetails['nil_depreciation'] ?? 'No',
                    'additional_towing_charges' => $insuranceDetails['additional_towing_charges'] ?? 0,
                    'policy_type' => $insuranceDetails['policy_type'] ?? 'Standalone Policy',
                    'zero_dep' => $insuranceDetails['zero_dep'] ?? 'No',
                ];
                $insuranceDetail = new InsuranceDetail();
                $insuranceDetail->claim_id = $claim->id;
                $insuranceDetail->fill($insuranceDetailData);
                $insuranceDetail->save();
            } else {
                $insuranceDetail = $existingInsuranceDetail;
            }*/

            $insurance = $claim->insurances;
            $insuranceDetail = InsuranceDetail::where('claim_id', $claim->id)->first();

            // If no insurance detail and OCR results exist, extract and save
            if (!$insuranceDetail && $claim->ocr_results != null) {
                $insuranceExtractor = new InsuranceDetailExtractor($claim->ocr_results);
                $insuranceDetails = $insuranceExtractor->extract();

                $insuranceDetailData = [
                    'policy_number' => $insuranceDetails['policy_number'] ?? 'N/A',
                    'previous_policy_number' => $insuranceDetails['previous_policy_number'] ?? '',
                    'insured_name' => $insuranceDetails['insured_name'] ?? 'N/A',
                    'insured_address' => $insuranceDetails['insured_address'] ?? '',
                    'insured_declared_value' => $insuranceDetails['insured_declared_value'] ?? '0',
                    'issuing_office_address_code' => $insuranceDetails['issuing_office_address_code'] ?? '',
                    'issuing_office_address' => $insuranceDetails['issuing_office_address'] ?? '',
                    'occupation' => $insuranceDetails['occupation'] ?? '',
                    'mobile' => $insuranceDetails['mobile'] ?? '',
                    'vehicle' => $insuranceDetails['vehicle'] ?? 'N/A',
                    'engine_no' => $insuranceDetails['engine_no'] ?? '',
                    'chassis_no' => $insuranceDetails['chassis_no'] ?? '',
                    'make' => $insuranceDetails['make'] ?? '',
                    'model' => $insuranceDetails['model'] ?? '',
                    'year_of_manufacture' => $insuranceDetails['year_of_manufacture'] ?? '',
                    'cubic_capacity' => $insuranceDetails['cubic_capacity'] ?? 0,
                    'seating_capacity' => $insuranceDetails['seating_capacity'] ?? 0,
                    'no_claim_bonus_percentage' => $insuranceDetails['no_claim_bonus_percentage'] ?? 0,
                    'nil_depreciation' => $insuranceDetails['nil_depreciation'] ?? 'No',
                    'additional_towing_charges' => $insuranceDetails['additional_towing_charges'] ?? 0,
                    'policy_type' => $insuranceDetails['policy_type'] ?? 'Standalone Policy',
                    'zero_dep' => $insuranceDetails['zero_dep'] ?? 'No',
                ];

                $insuranceDetail = new InsuranceDetail();
                $insuranceDetail->claim_id = $claim->id;
                $insuranceDetail->fill($insuranceDetailData);
                $insuranceDetail->save();
            }

            // Fallback: if still null, use empty model to avoid null errors in Blade
            $insuranceDetail = $insuranceDetail ?? new InsuranceDetail();
            
            // Process DL details using the extractor for the OCR results
            $dlExtractor = new DrivingLicenseDetailExtractor($claim->ocr_results);  // Assuming DL extractor exists
            $dlDetails = $dlExtractor->extract();
    
            // Check if DL details already exist for the claim
            $existingDlDetail = DlDetail::where('claim_id', $claim->id)->first();
            if (!$existingDlDetail) {
                $dlDetailData = [
                    'license_number' => $dlDetails['license_number'] ?? 'N/A',
                    'name' => $dlDetails['name'] ?? 'N/A',
                    'father_name' => $dlDetails['father_name'] ?? 'N/A',
                    'address' => $dlDetails['address'] ?? 'N/A',
                    'vehicle_class' => $dlDetails['vehicle_class'] ?? 'N/A',
                    'state_code' => $dlDetails['state_code'] ?? 'N/A',
                    'license_type' => $dlDetails['license_type'] ?? 'N/A',
                ];
                // Create and save DL details
                $dlDetail = new DlDetail();
                $dlDetail->claim_id = $claim->id;
                $dlDetail->fill($dlDetailData);
                $dlDetail->save();
            } else {
                $dlDetail = $existingDlDetail;
            }
            // Damage Results (if applicable)
            $damageResults = json_decode($claim->damage_result, true);
            
            //07-feb-2025 add by tanuja
            $damageResultsAll = json_decode($claim->all_damage_result, true);
            // dd($damageResultsAll);
            $damageTableResult = $damageResultsAll['damageTableData'] ?? [];
            $labourTableResult = $damageResultsAll['labourTableData'] ?? [];
            $summaryTableResult = $damageResultsAll['summaryTableData'] ?? [];
            $vehicleDepreciationData = $damageResultsAll['vehicleDepreciation'] ?? [];
            $paintDepreciationData = $damageResultsAll['paintDepreciation'] ?? [];
            $depreciationTypeData = $damageResultsAll['depreciationType'] ?? [];
            
            if (!empty($insuranceDetail->make)) {
                $parts = Part::where('brand', $insuranceDetail->make)->where('model', $insuranceDetail->model)->get();
                if ($parts->isEmpty()) {
                    $parts = Part::all()->take(40)->each(function ($part) {
                        $part->rate = 0; // Set price to 0 for each part
                    });
                }
            } else {
                $parts = Part::all(); 
            }

            $vehicleDepreciation = VehicleDepreciation::all();

            //fetch data fees bill
            $feesBillData = ProfessionalFee::where('claim_id', $claim->id)->first();
            
            // Return the view with all the necessary data
            return view('claim.show', compact(
                'claim',
                'insurance',
                'damageResults',
                'damageTableResult',
                'labourTableResult',
                'summaryTableResult',
                'parts',
                'vehicleDepreciation',
                'vehicleDepreciationData',
                'paintDepreciationData',
                'depreciationTypeData',
                'insuranceDetail',
                'dlDetail',
                'cleanedOcrResults',
                'numberPlate',
                'vehicleNumber',
                'feesBillData',
            ));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    //07-feb-2025 add by tanuja
    // Controller Method to Handle the Data Update
    public function updateAllData(Request $request, $claimId)
    {
        $request->validate([
            'all_data' => 'required|string', // Ensure the data is a valid string
        ]);

        // Decode the JSON data
        $allData = json_decode($request->input('all_data'), true);

        // Find the claim by its ID
        $claim = Claim::findOrFail($claimId);

        $claim->all_damage_result = json_encode($allData);
        $claim->save();

        return response()->json(['success' => true]);
    }

    public function partCreate($claimId)
    {
        $claim = Claim::find($claimId);
        $parts = Part::all();
        return view('claim.part_create', compact('claimId', 'status', 'parts'));
    }

    public function addPart(Request $request, $id)
    {
        if (\Auth::user()->can('edit claim')) {
            $claim = Claim::findOrFail($id);
            $damageResults = json_decode($claim->damage_result, true) ?? [];

            // Add new part to damage results
            $damageResults[] = $request->input('part');

            // Update claim with new damage results
            $claim->damage_result = json_encode($damageResults);
            $claim->save();

            // Calculate new totals
            $totalPrice = array_sum(array_column($damageResults, 'price'));
            $totalLabour = array_sum(array_column($damageResults, 'labour'));
            $totalPaintSum = array_sum(array_column($damageResults, 'paint'));
            $totalPaint = $totalPaintSum -(($totalPaintSum * $claim->paint_depreciation)/100);
            // Calculate total tax
            $totalTax = 0;
            foreach ($damageResults as $part) {
                // Calculate the tax for each part
                if (isset($part['price']) && isset($part['tax'])) {
                    $totalTax += $part['price'] * ($part['tax'] / 100); // tax = price * (tax rate / 100)
                }
            }
            return response()->json([
                'success' => true,
                'totalPrice' => $totalPrice,
                'totalLabour' => $totalLabour,
                'totalPaint' => $totalPaint,
                'totalTax' => $totalTax
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }
    }
    
    public function updatePartDetails(Request $request, $id)
    {
        if (!\Auth::user()->can('edit claim')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }
    
        $claim = Claim::findOrFail($id);
        $damageResults = json_decode($claim->damage_result, true) ?? [];
        $index = $request->input('index');
    
        if (isset($damageResults[$index])) {
            // Update the specific part's details
            $damageResults[$index]['paint'] = $request->input('paint');
            $damageResults[$index]['tax'] = $request->input('tax');
            $damageResults[$index]['labour'] = $request->input('labour');
            $damageResults[$index]['price'] = $request->input('price');
    
            $claim->damage_result = json_encode($damageResults);
            $claim->save();
    
            // Calculate new totals
            $totalPrice = array_sum(array_column($damageResults, 'price'));
            $totalLabour = array_sum(array_column($damageResults, 'labour'));
            $totalPaintSum = array_sum(array_column($damageResults, 'paint'));
            $totalPaint = $totalPaintSum -(($totalPaintSum * $claim->paint_depreciation)/100);
            // Calculate total tax
            $totalTax = 0;
            foreach ($damageResults as $part) {
                // Calculate the tax for each part
                if (isset($part['price']) && isset($part['tax'])) {
                    $totalTax += $part['price'] * ($part['tax'] / 100); // tax = price * (tax rate / 100)
                }
            }
            return response()->json([
                'success' => true,
                'totalPrice' => $totalPrice,
                'totalLabour' => $totalLabour,
                'totalPaint' => $totalPaint,
                'totalTax' => $totalTax
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Part not found.'], 404);
    }

    //07-feb-2025 add by tanuja
    public function removePart(Request $request, $id)
    {
        if (\Auth::user()->can('edit claim')) {
            $claim = Claim::findOrFail($id);
            $damageResults = json_decode($claim->all_damage_result, true) ?? [];
    
            // Get the index of the part to remove
            $indexToRemove = $request->input('index');
    
            // Remove the part from damageResults
            if (isset($damageResults['damageTableData'][$indexToRemove])) {
                array_splice($damageResults['damageTableData'], $indexToRemove, 1); // Remove the part at the given index
            }
    
            // Update the claim with the new damage results
            $claim->all_damage_result = json_encode($damageResults);
            $claim->save();

            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }
    }

    //07-feb-2025 add by tanuja
    public function removeLabourPart(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);
        $damageResults = json_decode($claim->all_damage_result, true) ?? [];

        
        // Get the index of the part to remove
        $indexToRemove = $request->input('index');

        // dd($damageResults['labourTableData'][$indexToRemove]);

        // Remove the part from damageResults
        if (isset($damageResults['labourTableData'][$indexToRemove])) {
            array_splice($damageResults['labourTableData'], $indexToRemove, 1); // Remove the part at the given index
        }

        // Update the claim with the new damage results
        $claim->all_damage_result = json_encode($damageResults);
        $claim->save();
        return response()->json([
            'success' => true
        ]);
    }
    
    
    public function updatePaintDepreciation(Request $request, $id)
    {
        // Validate the incoming request to ensure it's a valid percentage string
        $validated = $request->validate([
            'paint_depreciation' => 'required|numeric',
        ]);

        // Find the claim and update its paint_depreciation column
        $claim = Claim::findOrFail($id);
        $claim->paint_depreciation = $validated['paint_depreciation'];  // Store the percentage as a string
        $claim->save();

        return response()->json(['success' => true, 'message' => 'Paint Depreciation updated successfully']);
    }


    public function edit(Claim $claim)
    {
        if (\Auth::user()->can('edit claim')) {

            $customer = User::where('parent_id', parentId())->where('type', 'customer')->get()->pluck('name', 'id');
            $customer->prepend(__('Select Customer'), '');

            $status = Claim::$status;
            $insurance_companies = InsuranceCompany::pluck('name', 'id');
            $states = State::pluck('name', 'id');
            $cities = City::where('state_id', $claim->state_id)->pluck('name', 'id');

            return view('claim.edit', compact('customer', 'status','claim','insurance_companies','states','cities'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit claim')) {
            $validator = \Validator::make(
                $request->all(), [
                    'date' => 'required',
                    'status' => 'required',
                    'loss_date' => 'nullable|date',
                    'location' => 'nullable|string',
                    'claim_amount' => 'nullable|numeric',
                    'policy_number' => 'nullable|string',
                    'mobile' => 'nullable|string',
                    'email' => 'nullable|email',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $claim = Claim::find($id);
            $oldValues = $claim->toArray();
            $claim->claim_id = $request->claim_id;
            $claim->date = $request->date;
            $claim->status = $request->status;
            $claim->notes = $request->notes;
            $claim->loss_date = $request->loss_date;
            $claim->location = $request->location;
            $claim->place_of_survey = $request->place_of_survey;
            $claim->claim_amount = $request->claim_amount;
            $claim->policy_number = $request->policy_number;
            $claim->mobile = $request->mobile;
            $claim->email = $request->email;
            $claim->workshop_email = $request->workshop_email;
            $claim->workshop_name = $request->workshop_name;
            $claim->workshop_address = $request->workshop_address;
            $claim->workshop_mobile_number = $request->workshop_mobile_number;
            $claim->ensurance_email = $request->ensurance_email;
            $claim->state_id = $request->state_id;
            $claim->city_id = $request->city_id;
            $claim->insurance_company_id = $request->insurance_company_id;

            $claim->save();
            $this->logClaimAction(
                $claim,
                'update',
                'Claim updated',
                $oldValues,
                $claim->toArray()
            );
            return redirect()->route('claim.index')->with('success', __('Claim successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Claim $claim)
    {
        if (\Auth::user()->can('delete claim')) {
            $claimData = $claim->toArray();
            $this->logClaimAction(
                $claim,
                'delete',
                'Claim deleted',
                $claimData,
                null
            );
            $id=$claim->id;
            ClaimDocument::where('claim',$id)->delete();
            $claim->delete();
            return redirect()->back()->with('success', 'Claim successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function claimNumber()
    {
        $latestClaim = Claim::where('parent_id', parentId())->latest()->first();
        if ($latestClaim == null) {
            return 1;
        } else {
            return $latestClaim->claim_id + 1;
        }
    }

    public function getInsurance(Request $request)
    {
        $insurances = Insurance::where('customer', $request->customer)->orderBy('id','desc')->get();
        $response=[];
        foreach ($insurances as $insurance) {
            $response[$insurance->id]=insurancePrefix().$insurance->insurance_id;
        }

        return response()->json($response);
    }

    public function documentCreate($claimId)
    {
        $claim=Claim::find($claimId);
        $insurance=Insurance::find($claim->insurance);
        $docTypes=!empty($insurance->policies)?explode(',',$insurance->policies->claim_required_document):[];
        $documentType=DocumentType::whereIn('id',$docTypes)->get()->pluck('title','id');
        $documentType->prepend(__('Select Document'),'');

        $status=Insurance::$docStatus;
        return view('claim.document_create', compact('claimId','status','documentType'));
    }

    public function documentStore(Request $request, $claimId)
    {
        if (\Auth::user()->can('create document')) {
            $validator = \Validator::make(
                $request->all(), [
                    'document_type' => 'required',
                    'document' => 'required',
                    'status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $document = new ClaimDocument();
            $document->claim = $claimId;
            $document->document_type = $request->document_type;
            $document->status = $request->status;

            if (!empty($request->document)) {
                $documentFilenameWithExt = $request->file('document')->getClientOriginalName();
                $documentFilename = pathinfo($documentFilenameWithExt, PATHINFO_FILENAME);
                $documentExtension = $request->file('document')->getClientOriginalExtension();
                $documentFileName = $documentFilename . '_' . time() . '.' . $documentExtension;
                $directory = storage_path('upload/document');
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }
                $request->file('document')->storeAs('upload/document/', $documentFileName);
                $document->document = $documentFileName;
            }
            $this->logClaimAction(
                Claim::find($claimId),
                'document_upload',
                'Document uploaded: ' . $request->document_type,
                null,
                ['document_type' => $request->document_type, 'filename' => $document->document]
            );
            $document->save();
            return redirect()->back()->with('success', __('Document successfully added.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function documentDestroy($claimId,$documentId)
    {
        if (\Auth::user()->can('delete document')) {
            $document=ClaimDocument::find($documentId);
            $document->delete();
            return redirect()->back()->with('success', 'Document successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function showUploadOtpForm($encryptedId)
    {
        try {
            $claimId = Crypt::decrypt($encryptedId);
            $claim = Claim::findOrFail($claimId);

            // Generate a 6-digit OTP
            $otp = rand(100000, 999999);
            Session::put("otp_$claimId", $otp);
            Session::put("otp_claim_id", $claimId);

            // Send OTP via SMS
            $mobile = $claim->mobile;
            if (substr($mobile, 0, 3) === '+91') $mobile = substr($mobile, 3);
            if (substr($mobile, 0, 1) === '0') $mobile = substr($mobile, 1);
            $mobile = '91' . $mobile;

            $authKey = env('SMSCOUNTRY_AUTHKEY');
            $authToken = env('SMSCOUNTRY_AUTHTOKEN');
            $senderId = env('SMSCOUNTRY_SENDERID');
            $auth = base64_encode("$authKey:$authToken");

            // $message = "Dear Customer, Your One-Time Password (OTP) to access the claim document upload portal is $otp. This OTP is valid for 10 minutes from the time of issuance. For your security, please do not share this code with anyone. Regards SafetyFirst";
            $message = "Dear Customer, Your One-Time Password (OTP) to access the claim please upload documents in SafetyFirst portal is $otp.This OTP is valid for 10 minutes. For your security, please do not share this code with anyone. Regards SafetyFirst.";

            Http::withHeaders([
                'Authorization' => "Basic $auth",
                'Content-Type' => 'application/json'
            ])->post("https://restapi.smscountry.com/v0.1/Accounts/$authKey/SMSes", [
                "Text" => $message,
                "Number" => $mobile,
                "SenderId" => $senderId,
                "TemplateId" => "1707174703017862364",
                "Is_Unicode" => false
            ]);

            return view('claim.enter_otp', compact('claimId', 'encryptedId'));

        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function verifyOtp(Request $request)
    {
        $claimId = $request->claim_id;
        $enteredOtp = $request->otp;
        $sessionOtp = Session::get("otp_$claimId");

        if ($enteredOtp == $sessionOtp) {
            Session::put("otp_verified_$claimId", true);
            return redirect()->route('claim.upload', ['id' => Crypt::encrypt($claimId)]);
        } else {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }
    public function expireOtpSession($claimId)
    {
        Session::forget("otp_$claimId");
        Session::forget("otp_claim_id");
        Session::forget("otp_generated_time_$claimId");
        Session::forget("otp_verified_$claimId");

        return response()->noContent(); // 204
    }

    // public function uploadForm($id)
    // {
    //     //07-feb-2025 add by tanuja
    //     $claimId = decrypt($id);

    //     if (!Session::get("otp_verified_$claimId")) {
    //         return redirect()->route('claim.upload.otp', ['id' => $id])
    //                         ->with('error', 'Please verify OTP first.');
    //     }

    //     // ✅ Clear OTP session after successful upload
    //     Session::forget("otp_verified_$claimId");
    //     Session::forget("otp_$claimId");
    //     Session::forget("otp_claim_id");

    //     $claimData = Claim::select('*')->where('id',$claimId)->get()->toArray();
    //     $user = \Auth::user();
    //     return view('claim.upload', compact('claimId','user','claimData'));
    // }

    public function uploadForm($id)
    {
        $claimId = decrypt($id);

        if (!Session::get("otp_verified_$claimId")) {
            return redirect()->route('claim.upload.otp', ['id' => $id])
                            ->with('error', 'Please verify OTP first.');
        }

        //Clear OTP session after successful access
        // Session::forget("otp_verified_$claimId");
        // Session::forget("otp_$claimId");
        // Session::forget("otp_claim_id");

        $claimData = Claim::select('*')->where('id', $claimId)->get()->toArray();
        $user = \Auth::user();

        // ✅ Encrypt again to use in Blade
        $encryptedId = encrypt($claimId);

        return view('claim.upload', compact('claimId', 'user', 'claimData', 'encryptedId'));
    }



    public function uploadDocument(Request $request)
    {
        try {
            // Handle vehicle number submission
            if ($request->input('document_type') === 'vehicle_number') {
                return $this->handleVehicleNumber($request);
            }
    
            // Handle cause of accident submission
            if ($request->input('document_type') === 'cause_of_accident') {
                return $this->handleCauseOfAccident($request);
            }
    
            // Handle FIR file upload separately
            if ($request->input('document_type') === 'fir-copy') {
                return $this->handleFIRFileUpload($request);
            }
    
            // Handle file uploads for other document types
            $documentType = $request->input('document_type');
            $validDocumentTypes = [
                'aadhaar', 'rcbook', 'pan_card','tax_receipt','sales_invoice','dl', 'other_dl','insurance', 'photos', 'video',
                'claimform', 'claimintimation', 'satisfactionvoucher', 'finalbill', 'number_plate', 'paymentreceipt','under_repair','final'
            ];
            if (!in_array($documentType, $validDocumentTypes)) {
                return response()->json(['error' => 'Invalid document type'], 400);
            }
            $validator = Validator::make($request->all(), [
                'claim_id' => 'required|exists:claims,id',
                // 'files' => 'required',
                // 'files.*' => 'mimes:jpg,jpeg,png,pdf,mp4,webm|max:10240', // 10MB max file size
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $claim = Claim::findOrFail($request->input('claim_id'));
            if (in_array($documentType, ['photos', 'under_repair', 'final'])) {
                $uploadedFiles = $this->handlePhotoUploadWithGeotag($request->file('files'), $request->input('geotags'), $request->input('captureTimes'), $claim->id,  $documentType);
            } else if($documentType === 'number_plate'){
                $uploadedFiles = $this->handleNumberPlateUploadWithGeotag($request->file('files'), $request->input('geotag'), $request->input('captureTime'), $claim->id);
            }else {
                $uploadedFiles = $this->handleFileUpload($request->file('files'), $documentType, $claim->id);
            }

    
            $this->updateClaimWithUploadedFiles($claim, $documentType, $uploadedFiles);

            if ($documentType === 'aadhaar' && !empty($uploadedFiles)) {
                $claimHash  = md5($claim->id);
                $folderCode = $this->getFolderCode($documentType);
                $aadhaarFilePath = config('constant.claim_upload_path') . "/$claimHash/{$folderCode}/" . $uploadedFiles[0];

                $decryptedPath = storage_path("app/tmp_aadhaar_ocr.jpg");
                $processedPath = storage_path("app/tmp_aadhaar_processed.jpg");

                try {
                    file_put_contents($decryptedPath, Crypt::decrypt(file_get_contents($aadhaarFilePath)));
                    $this->preprocessImage($decryptedPath, $processedPath);

                    // ⬇️ Call improved method that also saves
                    $this->extractAadhaarDetailsAndSave($claim, $processedPath);

                } catch (\Exception $e) {
                    Log::error("Failed to extract Aadhaar info: " . $e->getMessage());
                } finally {
                    if (file_exists($decryptedPath)) unlink($decryptedPath);
                    if (file_exists($processedPath)) unlink($processedPath);
                }
            }
            
            // Check if all documents are uploaded and update status if necessary
            if ($this->allDocumentsUploaded($claim)) {
                $claim->status = 'documents_submitted';
                $claim->save();
            }
            $this->logClaimAction(
                $claim,
                'document_upload',
                'Document uploaded via customer portal: ' . $documentType,
                null,
                ['document_type' => $documentType, 'files' => $uploadedFiles]
            );
            return response()->json([
                'message' => ucfirst($documentType) . ' uploaded successfully',
                'files' => $uploadedFiles,
                'status' => $claim->status,
                'vehicleNumber' => $documentType === 'number_plate' && isset($uploadedFiles[0]['vehicleNumber']) ? $uploadedFiles[0]['vehicleNumber'] : null
            ]);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred during upload'], 500);
        }
    }

    private function preprocessImage($inputPath, $outputPath)
    {
        $image = imagecreatefromjpeg($inputPath);
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imagefilter($image, IMG_FILTER_CONTRAST, -50);
        imagejpeg($image, $outputPath);
        imagedestroy($image);
    }

    private function extractAadhaarDetailsAndSave($claim, $imagePath)
    {
        try {
            $ocrText = (new \thiagoalessio\TesseractOCR\TesseractOCR($imagePath))
                ->executable('C:\\Program Files\\Tesseract-OCR\\tesseract.exe') // Change path if needed
                ->lang('eng')
                ->run();

            Log::info("OCR Text:\n" . $ocrText);

            // ✅ Extract Aadhaar number
            preg_match('/\b[0-9]{4}\s[0-9]{4}\s[0-9]{4}\b/', $ocrText, $aadhaarNumberMatches);
            $aadhaarNumber = $aadhaarNumberMatches[0] ?? null;

            // ✅ Extract Aadhaar name using line before DOB
            $lines = array_values(array_filter(array_map('trim', explode("\n", $ocrText))));
            $aadhaarName = null;

            foreach ($lines as $index => $line) {
                if (stripos($line, 'DOB') !== false && isset($lines[$index - 1])) {
                    $potentialName = $lines[$index - 1];

                    if (preg_match('/^[a-zA-Z\s.]+$/', $potentialName)) {
                        $aadhaarName = trim($potentialName);
                        break;
                    }

                    // Fallback upward scan
                    for ($i = $index - 2; $i >= 0; $i--) {
                        if (preg_match('/^[a-zA-Z\s.]+$/', $lines[$i])) {
                            $aadhaarName = trim($lines[$i]);
                            break 2;
                        }
                    }
                }
            }

            // Fallback regex-based guess
            if (!$aadhaarName) {
                preg_match('/[A-Z][a-z]+\s+[A-Z][a-z]+/', $ocrText, $nameMatches);
                $aadhaarName = $nameMatches[0] ?? null;
            }

            // ✅ Save into claim if available
            if ($aadhaarNumber || $aadhaarName) {
                $claim->aadhaar_number = str_replace(' ', '', $aadhaarNumber);
                $claim->aadhaar_name = $aadhaarName;
                $claim->save();
            }

        } catch (\Exception $e) {
            Log::error("Aadhaar OCR extraction failed: " . $e->getMessage());
        }
    }


    private function handleVehicleNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'claim_id' => 'required|exists:claims,id',
            'vehicle_number' => 'required|string|max:15', 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $claim = Claim::findOrFail($request->input('claim_id'));
        $claim->vehicle_number = $request->input('vehicle_number'); 
        $claim->save();

        return response()->json([
            'message' => 'Vehicle number submitted successfully',
            'status' => $claim->status,
            'vehicle_number' => $claim->vehicle_number
        ]);
    }
    private function handleCauseOfAccident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'claim_id' => 'required|exists:claims,id',
            'cause_of_accident' => 'required|string|max:900', 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $claim = Claim::findOrFail($request->input('claim_id'));
        $claim->cause_of_accident = $request->input('cause_of_accident');
        $claim->save();

        return response()->json([
            'message' => 'Cause of accident submitted successfully',
            'status' => $claim->status,
        ]);
    }

    private function handleFIRFileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'claim_id' => 'required|exists:claims,id',
            // 'files' => 'required',
            // 'files.*' => 'mimes:jpg,jpeg,png,pdf,mp4,webm|max:10240', // 1MB max file size for FIR
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $claim = Claim::findOrFail($request->input('claim_id'));
        $firFile = $request->file('files');
        // dd($firFile);
        // Handle FIR file upload
        // $firFilename = $this->handleSingleFileUpload($firFile, 'fir');
        $firFilename = $this->handleSingleFileUpload($request->file('files'), 'fir', $claim->id);
        
        // Update the FIR file column in the database
        $claim->fir_file = $firFilename;
        $claim->save();

        return response()->json([
            'message' => 'FIR file uploaded successfully',
            'fir_file' => $firFilename,
            'status' => $claim->status
        ]);
    }

    private function handleFileUpload($files, $documentType, $claimId)
    {
        $uploadedFiles = [];
        $claimHash  = md5($claimId);
        $folderCode = $this->getFolderCode($documentType);
        $dir = config('constant.claim_upload_path') . "/$claimHash/{$folderCode}";

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach ($files as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = md5($originalName . time() . rand()) . '.' . $extension;
            $filePath = $dir . '/' . $fileNameToStore;

            //Encrypt file contents before storing
            $encryptedContent = Crypt::encrypt(file_get_contents($file));
            file_put_contents($filePath, $encryptedContent);

            $uploadedFiles[] = $fileNameToStore;
        }

        return $uploadedFiles;
    }

    private function handleSingleFileUpload($files, $documentType, $claimId)
    {
        $claimHash  = md5($claimId);
        $folderCode = $this->getFolderCode($documentType);
        $dir        = config('constant.claim_upload_path') . "/$claimHash/{$folderCode}";

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $uploadedFiles = [];

        // If it's a single file (not an array), wrap it
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            $filenameWithExt = $file->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $file->getClientOriginalExtension();
            $fileNameToStore = md5($filename . time() . rand()) . '.' . $extension;

            // 🔐 Encrypt the file before saving
            $encryptedContent = Crypt::encrypt(file_get_contents($file));
            file_put_contents($dir . '/' . $fileNameToStore, $encryptedContent);

            $uploadedFiles[] = $fileNameToStore;
        }

        return count($uploadedFiles) === 1 ? $uploadedFiles[0] : $uploadedFiles;
    }

    function getFolderCode($documentType)
    {
        $codeMap = [
            'number_plate'        => 'NPX',
            'aadhaar'             => 'AAX',
            'pan_card'            => 'PNX',
            'tax_receipt'         => 'TXP',
            'sales_invoice'       => 'SIV',
            'dl'                  => 'DLX',
            'other_dl'            => 'DLX',
            'rcbook'              => 'RCB',
            'insurance'           => 'INC',
            'claimform'           => 'CMF',
            'claimintimation'     => 'CMI',
            'satisfactionvoucher' => 'SFV',
            'fir'                 => 'FRC',
            'paymentreceipt'      => 'PYR',
            'finalbill'           => 'FBL',
            'video'               => 'VDX',
        ];
        return $codeMap[$documentType] ?? strtoupper(substr($documentType, 0, 3));
    }

    public function showImage($claimHash, $folder1, $folder2, $filename)
    {
        $basePath = config('constant.claim_upload_path');
        
        // Aadhaar format (no subfolder): {claimHash}/{folder1}/{filename}
        if ($folder2 === 'null') {
            $path = "{$basePath}/{$claimHash}/{$folder1}/{$filename}";
        } else {
            // Photo format: {claimHash}/{folder1}/{folder2}/{filename}
            $path = "{$basePath}/{$claimHash}/{$folder1}/{$folder2}/{$filename}";
        }

        if (file_exists($path)) {
            return $this->decryptAndReturnImage($path);
        }

        abort(404, 'Image not found');
    }

    // Helper function
    private function decryptAndReturnImage($path)
    {
        try {
            $decryptedContent = Crypt::decrypt(file_get_contents($path));
            $finfo = finfo_open();
            $mimeType = finfo_buffer($finfo, $decryptedContent, FILEINFO_MIME_TYPE);
            finfo_close($finfo);

            return response($decryptedContent)->header('Content-Type', $mimeType);
        } catch (\Exception $e) {
            abort(404, 'Invalid image');
        }
    }

    

    private function handlePhotoUploadWithGeotag($files, $geotags, $captureTimes, $claimId, $photoType = 'vehicle')
    {
        $uploadedFiles = [];

        // Step 1: Folder code mapping
        $photoTypeMap = [
            'vehicle'       => 'VPH', // or VPH (Vehicle Photo)
            'under_repair'  => 'URP',
            'final'         => 'FIP',
        ];

        $folderCode = $photoTypeMap[$photoType] ?? 'VPH';

        // Optional: claim hash to obscure claim ID
        $claimHash  = md5($claimId);

        // Final secure storage path
        $folderName   = "{$folderCode}";
        $storagePath  = config('constant.claim_upload_path') ."/{$claimHash}/PHX/{$folderName}";
        $pdfPath      = storage_path('app/photos/pdf');

        // Ensure directories exist
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0777, true);
        }

        // Step 2: Process each photo
        foreach ($files as $i => $file) {
            $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext      = $file->getClientOriginalExtension();
            $stored   = md5($origName . time() . rand()) . '.' . $ext;

            // Encrypt content and save
            $encryptedContents = Crypt::encrypt(file_get_contents($file));
            file_put_contents($storagePath . '/' . $stored, $encryptedContents);

            // Geotag parsing
            $geoJson = $geotags[$i] ?? null;
            $geotag  = null;
            if ($geoJson && $geoJson !== 'null') {
                $decoded = json_decode($geoJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid geotag JSON at index {$i}: {$geoJson}");
                }
                $geotag = $decoded;
            }

            $uploadedFiles[] = [
                'filename'    => $stored,
                'captureTime' => $captureTimes[$i] ?? null,
                'geotag'      => $geotag,
            ];
        }

        // Step 3: Generate PDF preview
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(10, 10, 10);

        $imagesPerPage = 6;
        $cellW = 90;
        $cellH = 60;

        foreach (array_chunk($uploadedFiles, $imagesPerPage) as $chunk) {
            $pdf->AddPage();
            $x = 10;
            $y = 10;

            foreach ($chunk as $idx => $photo) {
                $encryptedPath = $storagePath . '/' . $photo['filename'];
                $decryptedTempPath = sys_get_temp_dir() . '/' . uniqid('photo_') . '.jpg';

                try {
                    $decryptedContent = Crypt::decrypt(file_get_contents($encryptedPath));
                    file_put_contents($decryptedTempPath, $decryptedContent);

                    list($pxW, $pxH) = getimagesize($decryptedTempPath);
                    $origW = $pxW * 25.4 / 96;
                    $origH = $pxH * 25.4 / 96;
                    $scale = min($cellW / $origW, $cellH / $origH);
                    $drawW = $origW * $scale;
                    $drawH = $origH * $scale;
                    $offsetX = $x + ($cellW - $drawW) / 2;
                    $offsetY = $y + ($cellH - $drawH) / 2;

                    $pdf->Image($decryptedTempPath, $offsetX, $offsetY, $drawW, $drawH);

                    unlink($decryptedTempPath);
                } catch (\Exception $e) {
                    continue;
                }

                // Layout: 2 per row
                if ((($idx + 1) % 2) === 0) {
                    $x = 10;
                    $y += $cellH + 10;
                } else {
                    $x += $cellW + 10;
                }
            }
        }

        // Save encrypted PDF
        $pdfFileName = $photoType . '_photos_pdf_' . time() . '.pdf';
        $pdf->Output('F', $pdfPath . '/' . $pdfFileName);

        return $uploadedFiles;
    }

    private function handleNumberPlateUploadWithGeotag($files, $geotags, $captureTimes, $claimId)
    {
        $uploadedFiles = [];
        $folderCode = $this->getFolderCode('number_plate');
        $claimHash  = md5($claimId);
        $storagePath = config('constant.claim_upload_path') . "/$claimHash/{$folderCode}";

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        // dd($files);
        $filenameWithExt = $files->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $files->getClientOriginalExtension();
        $fileNameToStore = md5($filename . time() . rand()) . '.' . $extension;
        $filePath = $storagePath . '/' . $fileNameToStore;

        //Encrypt the file contents
        $encryptedContent = Crypt::encrypt(file_get_contents($files));
        file_put_contents($filePath, $encryptedContent);

        $geotag = json_decode($geotags, true);

        $uploadedFiles[] = [
            'filename'     => $fileNameToStore,
            'geotag'       => $geotag,
            'captureTime'  => $captureTimes
        ];

        // Extract vehicle number
        // $ocrText = $this->extractTextFromImage($filePath); // This should already decrypt inside the function
        // $vehicleNumber = $this->extractVehicleNumber($ocrText);
        // $uploadedFiles[0]['vehicleNumber'] = $vehicleNumber;

        return $uploadedFiles;
    }
    private function extractTextFromImage($imagePath)
    {
        // Perform OCR request to the OCR.space API
        $response = $this->client->request('POST', 'https://api.ocr.space/parse/image', [
            'headers' => ['apikey' => $this->ocrApiKey],
            'multipart' => [
                ['name' => 'file', 'contents' => fopen($imagePath, 'r')],
                ['name' => 'language', 'contents' => 'eng'],
                ['name' => 'isOverlayRequired', 'contents' => 'false'],
            ],
        ]);

        // Decode the JSON response
        $result = json_decode($response->getBody(), true);
        
        // Check if OCR text extraction was successful
        if (isset($result['ParsedResults'][0]['ParsedText'])) {
            return $result['ParsedResults'][0]['ParsedText'];
        }

        // If no text was extracted, return an empty string
        return '';
    }

    private function extractVehicleNumber($text)
    {
        // Define a regex pattern that matches common vehicle number plate formats
        // The pattern assumes a mix of letters and numbers, and it can be modified depending on the region
        $pattern = '/([A-Za-z0-9]{2,7}[\s\-]?[A-Za-z0-9]{2,5})/';

        // Perform the regex match
        preg_match_all($pattern, $text, $matches);

        // Check if any matches were found
        if (!empty($matches[0])) {
            // Return the first match, assuming it is the vehicle number plate
            return $matches[0][0];
        }

        // If no number plate-like text is found, return null
        return null;
    }

    private function updateClaimWithUploadedFiles(Claim $claim, $documentType, $uploadedFiles)
    {
        $updateField = $this->getUpdateField($documentType);
        // dd($updateField);
        if ($updateField === 'insurance_file' || $updateField === 'video_file' || $updateField === 'claim_form_file' || $updateField === 'claim_intimation_file' || $updateField === 'satisfaction_voucher_file' || $updateField === 'final_bill_files') {
            $claim->$updateField = $uploadedFiles[0];
        } elseif ($updateField === 'photo_files' && $updateField === 'number_plate_file') {
            $existingFiles = json_decode($claim->$updateField ?? '[]', true);
            $updatedFiles = array_merge($existingFiles, $uploadedFiles);
            $claim->$updateField = json_encode($updatedFiles);
        } else {
            $existingFiles = json_decode($claim->$updateField ?? '[]', true);
            $existingFiles = is_array($existingFiles) ? $existingFiles : [];
            $updatedFiles = array_merge($existingFiles, $uploadedFiles);
            $claim->$updateField = json_encode($updatedFiles);
        }
        $claim->save();
    }

    private function getUpdateField($documentType)
    {
        $fieldMap = [
            'aadhaar' => 'aadhaar_files',
            'pan_card' => 'pancard_file',
            'rcbook' => 'rcbook_files',
            'tax_receipt' => 'tax_receipt_file',
            'sales_invoice' => 'sales_invoice_file',
            'dl' => 'dl_files',
            'other_dl' => 'other_dl_files',
            'insurance' => 'insurance_file',
            'photos' => 'photo_files',
            'under_repair' => 'under_repair_photo_files',
            'final' => 'final_photo_files',
            'video' => 'video_file',
            'claimform' => 'claim_form_file',
            'claimintimation' => 'claim_intimation_file',
            'satisfactionvoucher' => 'satisfaction_voucher_file',
            'finalbill' => 'final_bill_files',
            'number_plate' => 'number_plate_file',
            'fir' => 'fir_file',
            'paymentreceipt' => 'payment_receipt_files',
        ];
        return $fieldMap[$documentType] ?? null;
    }

    private function allDocumentsUploaded(Claim $claim)
    {
        $requiredFields = [
            'aadhaar_files',
            'rcbook_files',
            'dl_files',
            'other_dl_files',
            'insurance_file',
            'photo_files',
            'under_repair_photo_files',
            'final_photo_files',
            'video_file',
            'cause_of_accident',
            'claim_form_file',
            'claim_intimation_file',
            'satisfaction_voucher_file',
            'final_bill_files',
            'number_plate_file',
            'payment_receipt_files',
        ];

        foreach ($requiredFields as $field) {
            if (empty($claim->$field)) {
                return false;
            }
        }

        return true;
    }

// public function generateReport($claimId)
// {
//     // Fetch the claim details from the database
//     $claim = Claim::findOrFail($claimId);

//     // Decode JSON data
//     $damageResults = json_decode($claim->damage_result, true);
//     $ocrResults = json_decode($claim->ocr_results, true);

//     // Clean OCR results
//     $cleanedOcrResults = $this->cleanOcrResults($ocrResults);
//     $latestVehicleDetails = VehicleRegistration::where('claim_id', $claimId)
//     ->orderBy('created_at', 'desc') // or 'updated_at', 'desc'
//     ->first();
//     $extractor = new InsuranceDetailExtractor($claim->ocr_results);
//     $insuranceDetails = $extractor->extract();
//     // Prepare the data to pass to the view
//     $data = [
//         'claim' => $claim,
//         'aadhaarFiles' => json_decode($claim->aadhaar_files) ?: [],
//         'rcBookFiles' => json_decode($claim->rcbook_files) ?: [],
//         'dlFiles' => json_decode($claim->dl_files) ?: [],
//         'insuranceFile' => $claim->insurance_file,
//         'photoFiles' => json_decode($claim->photo_files, true) ?: [],
//         'processedImageFiles' => json_decode($claim->processed_image_files) ?: [],
//         'videoFile' => $claim->video_file,
//         'damageResults' => $damageResults,
//         'ocrResults' => $cleanedOcrResults,
//         'insuranceDetails'=>$insuranceDetails,
//         'latestVehicleDetails'=> $latestVehicleDetails,
//     ];

//     // Create a new instance of Mpdf
//     $mpdf = new \Mpdf\Mpdf();

//     // Load the view and render it as HTML
//     $html = view('claim.report', $data)->render();
//     $mpdf->WriteHTML($html);

//     // // Merge the PDF files (insurance, claim_form, claim_intimation, satisfaction_voucher, consent_form)
//     // $this->mergePdfFile($mpdf, $claim->claim_form_file, 'claimform');
//     // $this->mergePdfFile($mpdf, $claim->claim_intimation_file, 'claimintimation');
//     // // $this->mergePdfFile($mpdf, $claim->satisfaction_voucher_file, 'satisfactionvoucher');
//     // $this->mergePdfFile($mpdf, $claim->consent_form_file, 'consentform');
//     // $this->mergePdfFile($mpdf, $claim->insurance_file, 'insurance');

//     // // Handle merging of Aadhaar, RC-book, and DL images (two images per page)
//     // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->aadhaar_files), 'aadhaar', 'Aadhaar');
//     // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->rcbook_files), 'rcbook', 'RC-Book');
//     // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->dl_files), 'dl', 'Driving License');

//     // // Handle photo files (up to 20 images)
//     // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->photo_files, true), 'photos', 'Photos', 20, true);

//     // // Handle processed image files (up to 20 images)
//     // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->processed_image_files), 'processed_image', 'Processed Images', 20);

//     // Output the generated PDF to the browser for download
//     return $mpdf->Output('claim_report_' . $claimId . '.pdf', 'D');
// }

    //added by tanuja 24-feb-2025
    public function generateExcel($claimId, Excel $excel)
    {
        return Excel::download(new ClaimReportExport($claimId), "claim_report_{$claimId}.xlsx");
    }

    // public function convertExcelToPdf($claimId)
    // {
    //     // 1. Save Excel file temporarily
    //     $fileName = "temp_claim_report_{$claimId}.xlsx";
    //     Excel::store(new ClaimReportExport($claimId), $fileName);
    //     $filePath = storage_path("app/{$fileName}");

    //     // 2. Load Excel
    //     $spreadsheet = IOFactory::load($filePath);
    //     $sheets = $spreadsheet->getAllSheets();

    //     // 3. Setup Mpdf
    //     $mpdf = new Mpdf([
    //         'format' => 'A4'
    //     ]);

    //     // 4. Loop through sheets
    //     foreach ($sheets as $index => $sheet) {
    //         $spreadsheet->setActiveSheetIndex($index);

    //         // Generate HTML for current sheet only
    //         $htmlWriter = new HtmlWriter($spreadsheet);
    //         $htmlWriter->setSheetIndex($index); // important!
            
    //         ob_start();
    //         $htmlWriter->save('php://output');
    //         $html = ob_get_clean();

    //         // if ($index > 0) {
    //         //     $mpdf->AddPage();
    //         // }

    //         $mpdf->WriteHTML($html);
    //     }

    //     // 5. Save PDF
    //     $pdfPath = storage_path("app/temp_claim_report_{$claimId}.pdf");
    //     $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);

    //     // 6. Cleanup
    //     unlink($filePath);

    //     // 7. Return for download
    //     return response()->download($pdfPath)->deleteFileAfterSend(true);
    // }

    // Convert the generated Excel file to PDF
    public function convertExcelToPdf($claimId)
    {
        $pdfExport = new ClaimReportPdfExport($claimId);
        return $pdfExport->download();
    }



    public function generateReport($claimId)
    {
        $claim = Claim::findOrFail($claimId);
        $damageResults = json_decode($claim->damage_result, true);
        
        //added by tanuja
        $damageResultsAll = json_decode($claim->all_damage_result, true);
        $damageTableResult = $damageResultsAll['damageTableData'] ?? [];
        $labourTableResult = $damageResultsAll['labourTableData'] ?? [];
        $summaryTableResult = $damageResultsAll['summaryTableData'] ?? [];
        
        $latestVehicleDetails = VehicleRegistration::where('claim_id', $claimId)
            ->orderBy('created_at', 'desc')
            ->first();
        $latestDlDetails = DlDetail::where('claim_id', $claimId)
            ->orderBy('created_at', 'desc')
            ->first();
        $latestInsuranceDetails = InsuranceDetail::where('claim_id', $claimId)
            ->orderBy('created_at', 'desc')
            ->first();

        // Calculate the vehicle's age
        if (!empty($latestVehicleDetails->registration_date)) {
            // Calculate the vehicle's age
            $registrationDate = new \Carbon\Carbon($latestVehicleDetails->registration_date);
            $currentDate = \Carbon\Carbon::now();
            $vehicleAgeInYears = $registrationDate->diffInMonths($currentDate) / 12; // Age in years
        
            // Get the depreciation percentage based on the vehicle's age
            $depreciationRecord = \DB::table('vehicle_depreciation')
                ->where('vehicle_age', '<=', $vehicleAgeInYears)
                ->orderBy('vehicle_age', 'desc') // Get the closest match without exceeding the age
                ->first();
            $depreciationPercentage = $depreciationRecord ? $depreciationRecord->depreciation_percentage : 0;
        } else {
            // If registration_date is not set, default vehicleAgeInYears to 0 and depreciation to 0
            $vehicleAgeInYears = 0;
            $depreciationPercentage = 0;
        }
        // Check if zero_dep is not empty and is set to "Yes"
        if (!empty($latestInsuranceDetails->zero_dep) && strtolower($latestInsuranceDetails->zero_dep) === "yes") {
            $depreciationPercentage = 0; // Set depreciation to 0 if zero_dep is "Yes"
        }
        $gst = Gst::first();

        // Fetch the Professional Fee Data for the given claim ID
        $feesBillData = ProfessionalFee::where('claim_id', $claimId)->first();

        $data = [
            'claim' => $claim,
            'damageResults' => $damageResults,
            'damageResultsAll' => $damageResultsAll,
            'damageTableResult' => $damageTableResult,
            'labourTableResult' => $labourTableResult,
            'summaryTableResult' => $summaryTableResult,
            'latestVehicleDetails' => $latestVehicleDetails,
            'latestDlDetails' => $latestDlDetails,
            'latestInsuranceDetails' => $latestInsuranceDetails,
            'aadhaarFiles' => json_decode($claim->aadhaar_files) ?: [],
            'rcBookFiles' => json_decode($claim->rcbook_files) ?: [],
            'dlFiles' => json_decode($claim->dl_files) ?: [],
            'insuranceFile' => $claim->insurance_file,
            'photoFiles' => json_decode($claim->photo_files, true) ?: [],
            'processedImageFiles' => json_decode($claim->processed_image_files) ?: [],
            'depreciationPercentage' => $depreciationPercentage,
            'gst' => $gst,
            'feesBillData' => $feesBillData,
        ];

        // Generate the HTML report using mPDF
        $mpdf = new \Mpdf\Mpdf();
        $html = view('claim.report', $data)->render();
        $mpdf->WriteHTML($html);

        // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->aadhaar_files), 'aadhaar', 'Aadhaar');
        // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->rcbook_files), 'rcbook', 'RC-Book');
        // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->dl_files), 'dl', 'Driving License');
        // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->photo_files, true), 'photos', 'Photos', 20, true);
        // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->processed_image_files), 'processed_image', 'Processed Images', 20);
        
        // Merge image files into the mPDF document
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->aadhaar_files), 'aadhaar', 'Aadhaar', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->pancard_file), 'pan_card', 'Pan Card', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->rcbook_files), 'rcbook', 'RC-Book', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->dl_files), 'dl', 'Driving License', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->other_dl_files), 'other_dl', 'Other Driving License', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->tax_receipt_file), 'tax_receipt', 'Tax Receipt', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->sales_invoice_file), 'sales_invoice', 'Sales Invoice', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->fir_file), 'fir', 'FIR Copy', 6, false, $id);

        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->photo_files, true), 'vehicle', 'Vehicle Photos', 6, true, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->under_repair_photo_files, true), 'under_repair', 'Under Repair Photos', 6, true, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->final_photo_files, true), 'final', 'Final Photos', 6, true, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->processed_image_files), 'processed_image', 'Processed Images', 6,$id);


        // Generate a unique temporary file name
        $uniqueId = uniqid();
        $mainReportPath = storage_path("temp/main_report_{$uniqueId}.pdf");
        $mpdf->Output($mainReportPath, \Mpdf\Output\Destination::FILE);

        // List of PDF files to merge
        $pdfFiles = [$mainReportPath];
        // $this->addPdfFileToList($pdfFiles, $claim->claim_form_file, 'claimform');
        // $this->addPdfFileToList($pdfFiles, $claim->claim_intimation_file, 'claimintimation');
        // $this->addPdfFileToList($pdfFiles, $claim->satisfaction_voucher_file, 'satisfactionvoucher');
        // $this->addPdfFileToList($pdfFiles, $claim->consent_form_file, 'consentform');
        // $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'insurance');
        
        $this->addPdfFileToList($pdfFiles, $claim->claim_form_file, 'claimform', $id);
        $this->addPdfFileToList($pdfFiles, $claim->claim_intimation_file, 'claimintimation', $id);
        $this->addPdfFileToList($pdfFiles, $claim->satisfaction_voucher_file, 'satisfactionvoucher', $id);
        $this->addPdfFileToList($pdfFiles, $claim->consent_form_file, 'consentform', $id);
        $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'insurance', $id);
        $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'paymentreceipt', $id);
        $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'finalbill', $id);

        // Generate a unique output file name
        $mergedPdfPath = storage_path("temp/claim_report_{$claimId}_{$uniqueId}.pdf");
        $this->mergePdfFilesWithGhostscript($pdfFiles, $mergedPdfPath);


        // Return the final merged PDF
        return response()->download($mergedPdfPath);
    }

    private function addPdfFileToList(&$pdfFiles, $fileName, $folder, $claimId)
    {
        if ($fileName) {
            $claimHash = md5($claimId);
            $folderCode = getFolderCode($folder);

            // Construct full path
            $filePath = config('constant.claim_upload_path') . "/{$claimHash}/{$folderCode}/{$fileName}";

            if (file_exists($filePath)) {
                $pdfFiles[] = $filePath;
            }
        }
    }

    private function mergePdfFilesWithGhostscript($inputFiles, $outputFile)
    {
        dd("hhj");
        $inputFilesString = implode(' ', array_map('escapeshellarg', $inputFiles));
        $command = "gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=" . escapeshellarg($outputFile) . " " . $inputFilesString;
        exec($command, $output, $status);
        $status = 0;
        if ($status !== 0) {
            throw new \Exception("Failed to merge PDF files using Ghostscript.");
            // throw new \Exception("Failed to merge PDF files using Ghostscript. Output: " . implode("\n", $output));
        }
    }
    private function mergePdfFile($mpdf, $fileName, $folder)
    {
        if ($fileName) {
            // Path to the PDF file
            $filePath = storage_path('upload/document/' . $folder . '/' . $fileName);
            
            // Check if the PDF exists and merge into the PDF
            if (file_exists($filePath)) {
                $pageCount = $mpdf->setSourceFile($filePath);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $templateId = $mpdf->importPage($pageNo);
                    $mpdf->AddPage();
                    $mpdf->useTemplate($templateId);
                }
            }
        }
    }
    

    public function downloadPhotosPdf(Claim $claim)
    {
        $photoSections = [
            'Vehicle Damage Photos' => ['key' => 'photo_files', 'folder' => 'VPH'],
            'Under Repair Photos'   => ['key' => 'under_repair_photo_files', 'folder' => 'URP'],
            'Final Photos'          => ['key' => 'final_photo_files', 'folder' => 'FIP'],
        ];

        $claimHash = md5($claim->id);
        // $yearMonth = now()->format('Ym');
        $basePath = config('constant.claim_upload_path');

        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetFont('Arial', '', 12);

        $imagesPerPage = 6;
        $cellW = 90;
        $cellH = 60;

        foreach ($photoSections as $sectionTitle => $info) {
            $photoJson = $claim->{$info['key']};
            if (empty($photoJson)) continue;

            $photos = json_decode($photoJson, true);
            if (!is_array($photos)) continue;

            // Start new page for each section
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 14); // Bold, larger font for heading
            $pdf->Cell(0, 10, $sectionTitle, 0, 1, 'C');
            $pdf->Ln(5); // space after heading

            $folderPath = "$basePath/$claimHash/PHX/{$info['folder']}";

            $x = 10;
            $y = 25;
            $count = 0;

            foreach ($photos as $idx => $photo) {
                if (!isset($photo['filename'])) continue;

                $photoPath = $folderPath . '/' . $photo['filename'];
                if (!file_exists($photoPath)) continue;

                $decryptedTempPath = sys_get_temp_dir() . '/' . uniqid('photo_') . '.jpg';

                try {
                    $decryptedContent = Crypt::decrypt(file_get_contents($photoPath));
                    file_put_contents($decryptedTempPath, $decryptedContent);

                    list($pxW, $pxH) = getimagesize($decryptedTempPath);
                    $origW = $pxW * 25.4 / 96;
                    $origH = $pxH * 25.4 / 96;
                    $scale = min($cellW / $origW, $cellH / $origH);
                    $drawW = $origW * $scale;
                    $drawH = $origH * $scale;
                    $offsetX = $x + ($cellW - $drawW) / 2;
                    $offsetY = $y + ($cellH - $drawH) / 2;

                    $pdf->Image($decryptedTempPath, $offsetX, $offsetY, $drawW, $drawH);
                    unlink($decryptedTempPath);
                } catch (\Exception $e) {
                    continue;
                }

                // Move to next cell
                if ((($count + 1) % 2) === 0) {
                    $x = 10;
                    $y += $cellH + 10;
                } else {
                    $x += $cellW + 10;
                }

                $count++;

                // If 6 images placed, start a new page (with no heading)
                if ($count % $imagesPerPage === 0 && $idx + 1 < count($photos)) {
                    $pdf->AddPage();
                    $x = 10;
                    $y = 10;
                }
            }
        }

        $filename = "claim_{$claim->id}_photos_" . now()->format('Ymd_His') . ".pdf";

        return response(
            $pdf->Output('S', $filename),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }

    /**
     * Merges encrypted image files into a PDF using mPDF.
     *
     * @param \Mpdf\Mpdf $mpdf               mPDF instance to write images into
     * @param array      $files              List of image files (array of filenames or array of ['filename' => string])
     * @param string     $folder             Logical folder type (e.g., 'aadhaar', 'vehicle', 'under_repair')
     * @param string     $label              Label to be used as a heading on each page (e.g., 'Aadhaar', 'Vehicle Photos')
     * @param int        $maxImages          Max images per page (default: 6, for grid layout)
     * @param bool       $includeMetadata    If true, assumes each $files[] is an array with 'filename'
     * @param int|null   $claimId            ID of the claim (used to generate folder hash)
     *
     * @return string Success or failure message
    */
    private function mergeImagesIntoPDF($mpdf, $files, $folder, $label, $maxImages = 6, $includeMetadata = false, $claimId = null)
    {
        if (empty($files)) {
            return "No files provided.";
        }

        $claimHash = md5($claimId);
        $imagesAdded = 0;

        $isGridLayout = in_array($folder, ['vehicle', 'under_repair', 'final']);

        // For grid layout (vehicle/under_repair/final)
        if ($isGridLayout) {
            $cols    = 2;
            $rows    = 3;
            $cellW   = 90;
            $cellH   = 65;
            $gapX    = 10;
            $gapY    = 10;
            $startX  = 15;
            $startY  = 25;

            foreach (array_chunk($files, $maxImages) as $pageImages) {
                $mpdf->AddPage();
                $mpdf->WriteHTML("<h4 style='text-align:center; margin-bottom:5mm;'>{$label}</h4>");

                $row = 0;
                $col = 0;

                foreach ($pageImages as $file) {
                    $filename = $includeMetadata ? $file['filename'] : $file;
                    $folderCode = getFolderCode($folder);

                    $encryptedPath = config('constant.claim_upload_path') . "/{$claimHash}/PHX/{$folderCode}/{$filename}";
                    if (!file_exists($encryptedPath)) continue;

                    try {
                        $tempPath = sys_get_temp_dir() . '/' . uniqid('pdfimg_') . '.jpg';
                        $decryptedContent = Crypt::decrypt(file_get_contents($encryptedPath));
                        file_put_contents($tempPath, $decryptedContent);

                        $x = $startX + $col * ($cellW + $gapX);
                        $y = $startY + $row * ($cellH + $gapY);

                        $mpdf->Image($tempPath, $x, $y, $cellW, $cellH, '', '', true, false);
                        unlink($tempPath);
                        $imagesAdded++;

                        $col++;
                        if ($col === $cols) {
                            $col = 0;
                            $row++;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
        // For Aadhaar, RC Book, DL, etc. (one image per page)
        else {
            foreach ($files as $file) {
                $filename = $includeMetadata ? $file['filename'] : $file;
                $folderCode = getFolderCode($folder);
                $encryptedPath = config('constant.claim_upload_path') . "/{$claimHash}/{$folderCode}/{$filename}";

                if (!file_exists($encryptedPath)) continue;

                try {
                    $tempPath = sys_get_temp_dir() . '/' . uniqid('pdfimg_') . '.jpg';
                    $decryptedContent = Crypt::decrypt(file_get_contents($encryptedPath));
                    file_put_contents($tempPath, $decryptedContent);

                    list($w, $h) = getimagesize($tempPath);
                    $maxW  = 190;
                    $maxH  = 277;
                    $scale = min($maxW / $w, $maxH / $h);
                    $newW  = $w * $scale;
                    $newH  = $h * $scale;
                    $x     = (210 - $newW) / 2;
                    $y     = (297 - $newH) / 2;

                    $mpdf->AddPage();
                    $mpdf->WriteHTML("<h4 style='text-align:center; margin-bottom:5mm;'>{$label}</h4>");
                    $mpdf->Image($tempPath, $x, $y, $newW, $newH, '', '', true, false);

                    unlink($tempPath);
                    $imagesAdded++;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $imagesAdded ? "Images added successfully: {$imagesAdded}" : "No valid images found.";
    }





    private function cleanOcrResults($ocrResults)
    {
        $cleanedResults = [];

        foreach ($ocrResults as $type => $files) {
            foreach ($files as $filePath => $data) {
                $cleanedText = $this->cleanText($data['text']);
                $cleanedResults[$type][$filePath] = [
                    'text' => $cleanedText,
                    'timestamp' => $data['timestamp'],
                ];
            }
        }

        return $cleanedResults;
    }

    private function cleanText($text)
    {
        $cleanedText = preg_replace('/[\r\n]+/', ' ', $text);
        $cleanedText = preg_replace('/\s+/', ' ', $cleanedText);

        $extractedData = [];

        if (preg_match('/Reg\.?\s*No\.?\s*([A-Z0-9]+)/i', $cleanedText, $matches)) {
            $extractedData['Reg No'] = $matches[1];
        }

        // Extract Chassis Number
        if (preg_match('/Chassis No\.?\s*([A-Z0-9]+)/i', $cleanedText, $matches)) {
            $extractedData['Chassis No'] = $matches[1];
        }

        // Extract Engine Number
        if (preg_match('/Engine No\.?\s*([A-Z0-9]+)/i', $cleanedText, $matches)) {
            $extractedData['Engine No'] = $matches[1];
        }

        // Extract Owner Name
        if (preg_match('/Owner Name\s*([A-Z\s]+)(?=\s+Son\/Daughter\/Wife)/i', $cleanedText, $matches)) {
            $extractedData['Owner Name'] = trim($matches[1]);
        }

        // Extract Guardian Name
        if (preg_match('/(?:Son\/Daughter\/Wife of|S\/D\/W of)\s*([A-Z\s]+)(?=\s+Address)/i', $cleanedText, $matches)) {
            $extractedData['Guardian Name'] = trim($matches[1]);
        }

        // Extract Address
        if (preg_match('/Address\s*(.+?)(?=\s+(?:Fuel Used|RJ \d{6}|$))/i', $cleanedText, $matches)) {
            $address = trim($matches[1]);
            $address = preg_replace('/Owner Sr\.? No\.?\s*\d+/i', '', $address);
            $extractedData['Address'] = trim($address);
        }

        // Extract additional information if available
        if (preg_match('/Maker\'s Name\s*(.+?)(?=\s+Month|$)/i', $cleanedText, $matches)) {
            $extractedData['Maker\'s Name'] = trim($matches[1]);
        }

        if (preg_match('/Model Name\s*(.+?)(?=\s+Colour|$)/i', $cleanedText, $matches)) {
            $extractedData['Model Name'] = trim($matches[1]);
        }

        if (preg_match('/Fuel Used\s*(\w+)/i', $cleanedText, $matches)) {
            $extractedData['Fuel Type'] = trim($matches[1]);
        }
        // License Number
        if (preg_match('/DL No\.\s*(MH\d{2}\s*\d+)/i', $text, $matches)) {
            $extractedData['License Number'] = trim($matches[1]);
        }

        // Name
        if (preg_match('/Name\s*([A-Z\s]+)(?=\s+S\/O\/W)/i', $text, $matches)) {
            $extractedData['Name'] = trim($matches[1]);
        }

        // Father's Name
        if (preg_match('/S\/O\/W\s*([A-Z\s]+)(?=\s+\d+)/i', $text, $matches)) {
            $extractedData['Father\'s Name'] = trim($matches[1]);
        }

        // Address
        if (preg_match('/(?:S\/O\/W\s*[A-Z\s]+\s*)([\w\s,]+MUMBAI)/i', $text, $matches)) {
            $extractedData['Address'] = trim($matches[1]);
        }

        // Date of Birth
        if (preg_match('/DOB\s*(\d{2}-\d{2}-\d{4})/i', $text, $matches)) {
            $extractedData['Date of Birth'] = trim($matches[1]);
        }

        // Date of Issue
        if (preg_match('/DOI\s*(\d{2}-\d{2}-\d{4})/i', $text, $matches)) {
            $extractedData['Date of Issue'] = trim($matches[1]);
        }

        // Valid Till
        if (preg_match('/Valid Till\s*(\d{2}-\d{2}-\d{4})/i', $text, $matches)) {
            $extractedData['Valid Till'] = trim($matches[1]);
        }

        if (preg_match('/Colour\s*([A-Z\s]+)/i', $cleanedText, $matches)) {
            $extractedData['Colour'] = trim($matches[1]);
        }

        // Extract Body Type
        if (preg_match('/Body Type\s*([A-Z\s]+)/i', $cleanedText, $matches)) {
            $extractedData['Body Type'] = trim($matches[1]);
        }

        // Extract Seating Capacity
        if (preg_match('/Seating Capacity\s*([0-9]+)/i', $cleanedText, $matches)) {
            $extractedData['Seating Capacity'] = trim($matches[1]);
        }

        // Extract Tax Paid Upto
        if (preg_match('/Tax Paid Upto\s*([A-Z0-9\s]+)/i', $cleanedText, $matches)) {
            $extractedData['Tax Paid Upto'] = trim($matches[1]);
        }

        // Extract Registration Authority (assuming it's the last entry or just after some keyword)
        if (preg_match('/Registration Authority\s*([A-Z\s]+)/i', $cleanedText, $matches)) {
            $extractedData['Registration Authority'] = trim($matches[1]);
        }


        return $extractedData;
    }

    private function logClaimAction($claim, $action, $description, $oldValues = null, $newValues = null)
    {
        ClaimLog::create([
            'claim_id' => $claim->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    //View the pdf report
    public function viewReport($id)
    {
        $claim = Claim::findOrFail($id);
        $damageResults = json_decode($claim->damage_result, true);
        
        //07-feb-2025 add by tanuja
        $damageResultsAll = json_decode($claim->all_damage_result, true);
        $damageTableResult = $damageResultsAll['damageTableData'] ?? [];
        $labourTableResult = $damageResultsAll['labourTableData'] ?? [];
        $summaryTableResult = $damageResultsAll['summaryTableData'] ?? [];
        
        $latestVehicleDetails = VehicleRegistration::where('claim_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $latestDlDetails = DlDetail::where('claim_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $latestInsuranceDetails = InsuranceDetail::where('claim_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        // Calculate the vehicle's age
        if (!empty($latestVehicleDetails->registration_date)) {
            // Calculate the vehicle's age
            $registrationDate = new \Carbon\Carbon($latestVehicleDetails->registration_date);
            $currentDate = \Carbon\Carbon::now();
            $vehicleAgeInYears = $registrationDate->diffInMonths($currentDate) / 12; // Age in years
        
            // Get the depreciation percentage based on the vehicle's age
            $depreciationRecord = \DB::table('vehicle_depreciation')
                ->where('vehicle_age', '<=', $vehicleAgeInYears)
                ->orderBy('vehicle_age', 'desc') // Get the closest match without exceeding the age
                ->first();
            $depreciationPercentage = $depreciationRecord ? $depreciationRecord->depreciation_percentage : 0;
        } else {
            // If registration_date is not set, default vehicleAgeInYears to 0 and depreciation to 0
            $vehicleAgeInYears = 0;
            $depreciationPercentage = 0;
        }
        // Check if zero_dep is not empty and is set to "Yes"
        if (!empty($latestInsuranceDetails->zero_dep) && $latestInsuranceDetails->zero_dep === "Yes") {
        $depreciationPercentage = 0; // Set depreciation to 0 if zero_dep is "Yes"
        }
        $gst = Gst::first();

        // Fetch the Professional Fee Data for the given claim ID
        $feesBillData = ProfessionalFee::where('claim_id', $id)->first();

        $data = [
            'claim' => $claim,
            'damageResults' => $damageResults,
            'damageResultsAll' => $damageResultsAll,
            'damageTableResult' => $damageTableResult,
            'labourTableResult' => $labourTableResult,
            'summaryTableResult' => $summaryTableResult,
            'latestVehicleDetails' => $latestVehicleDetails,
            'latestDlDetails' => $latestDlDetails,
            'latestInsuranceDetails' => $latestInsuranceDetails,
            'depreciationPercentage' => $depreciationPercentage,
            'gst' => $gst,
            'feesBillData' => $feesBillData,
        ];

        // Generate the main report
        $mpdf = new \Mpdf\Mpdf();
        $html = view('claim.report', $data)->render();
        $mpdf->WriteHTML($html);

        // Merge images into the PDF
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->aadhaar_files), 'aadhaar', 'Aadhaar', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->pancard_file), 'pan_card', 'Pan Card', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->rcbook_files), 'rcbook', 'RC-Book', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->dl_files), 'dl', 'Driving License', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->other_dl_files), 'other_dl', 'Other Driving License', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->tax_receipt_file), 'tax_receipt', 'Tax Receipt', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->sales_invoice_file), 'sales_invoice', 'Sales Invoice', 6, false, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->fir_file), 'fir', 'FIR Copy', 6, false, $id);

        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->photo_files, true), 'vehicle', 'Vehicle Photos', 6, true, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->under_repair_photo_files, true), 'under_repair', 'Under Repair Photos', 6, true, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->final_photo_files, true), 'final', 'Final Photos', 6, true, $id);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->processed_image_files), 'processed_image', 'Processed Images', 6,$id);

        // Save the main report to a unique temporary file
        $uniqueId = uniqid();
        $mainReportPath = storage_path("temp/main_report_{$uniqueId}.pdf");
        $mpdf->Output($mainReportPath, \Mpdf\Output\Destination::FILE);

        dd("hhh");
        // List of PDF files to merge
        $pdfFiles = [$mainReportPath];
        $this->addPdfFileToList($pdfFiles, $claim->claim_form_file, 'claimform', $id);
        $this->addPdfFileToList($pdfFiles, $claim->claim_intimation_file, 'claimintimation', $id);
        $this->addPdfFileToList($pdfFiles, $claim->satisfaction_voucher_file, 'satisfactionvoucher', $id);
        $this->addPdfFileToList($pdfFiles, $claim->consent_form_file, 'consentform', $id);
        $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'insurance', $id);
        $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'paymentreceipt', $id);
        $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'finalbill', $id);

        // Generate a unique output file
        $mergedPdfPath = storage_path("temp/claim_report_{$id}_{$uniqueId}.pdf");
        $this->mergePdfFilesWithGhostscript($pdfFiles, $mergedPdfPath);

        // Serve the merged PDF as an inline response
        return response()->file($mergedPdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="claim_report_' . $id . '.pdf"',
        ]);
    }

    // Method to handle the recheck action
    public function checkVehicleInsuranceMatch($claimId)
    {
        // Retrieve the vehicle registration details for the claim
        $vehicle = VehicleRegistration::where('claim_id', $claimId)->first();
        
        // Retrieve the insurance details for the claim
        $insurance = InsuranceDetail::where('claim_id', $claimId)->first();

        // Retrieve the claim details to get the loss date
        $claim = Claim::where('id', $claimId)->first();

        // Retrieve the license details based on claim_id (assuming it's in a 'vehicle_licenses' table)
        $license = DlDetail::where('claim_id', $claimId)->first();

        // Check if all necessary data is available
        $mismatches = [];

        if ($vehicle && $insurance && $claim && $license) {
            // Compare vehicle and insurance details
            if ($vehicle->vehicle_chasi_number !== $insurance->chassis_no) {
                $mismatches[] = 'Chassis Number does not match.';
                $claim->status = 'documents_mismatched';
            }

            if ($vehicle->vehicle_engine_number !== $insurance->engine_no) {
                $mismatches[] = 'Engine Number does not match.';
                $claim->status = 'documents_mismatched';
            }

            // Check if the insurance is active at the time of the loss date
            $lossDate = $claim->loss_date;

            // Ensure that the insurance dates are valid
            if ($insurance->insurance_start_date > $lossDate || $insurance->insurance_expiry_date < $lossDate) {
                $mismatches[] = 'Insurance is not active at the time of the loss date.';
                $claim->status = 'rejected';
            }

            // Check if the vehicle license is valid at the time of the loss date
            $licenseValidityDate = $license->validity_date;

            if ($licenseValidityDate < $lossDate) {
                $mismatches[] = 'Vehicle license has expired before the loss date.';
                $claim->status = 'rejected';
            }

        } else {
            $mismatches[] = 'Vehicle, Insurance, License, or Claim data is missing.';
            $claim->status = 'rejected';
        }

        // Return a response based on the result of the comparison
        if (!empty($mismatches)) {
            // Join all mismatch messages with line breaks for a clearer alert message
            $errorMessage = implode('<br>', $mismatches);
            return redirect()->back()->with('error', $errorMessage);
        }

        // If all matches, update the claim notes and return success
        $claim->notes = 'Vehicle, insurance, and license details match successfully.';
        $claim->status = 'approved';
        $claim->save();

        return redirect()->back()->with('success', __('Vehicle and insurance details match.'));
    }
    public function updateDocument(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'claim_id' => 'required|exists:claims,id',
                'document_type' => 'required|in:aadhaar,rcbook,pan_card,tax_receipt,sales_invoice,dl,insurance,claimform,claimintimation,consentform,satisfactionvoucher,fir,number_plate,finalbill,paymentreceipt',
                'file_to_update' => 'required|string', // filename of the document to be updated
                'new_file' => 'required|mimes:jpg,jpeg,png,pdf,mp4,webm|max:10240', // 10MB max file size
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $claim = Claim::findOrFail($request->input('claim_id'));
            $documentType = $request->input('document_type');
            $fileToUpdate = $request->input('file_to_update');
            $newFile = $request->file('new_file');

            // Get the appropriate field based on document type
            $updateField = $this->getUpdateField($documentType);

            // Retrieve existing files (if any)
            $existingFiles = json_decode($claim->$updateField ?? '[]', true);

            // If it's a single file document type (like insurance), ensure the field is a string, not an array
            if (in_array($documentType, ['insurance', 'claimform', 'claimintimation', 'satisfactionvoucher', 'consentform', 'fir','finalbill'])) {
                // Remove the old file from storage
                $oldFilePath = storage_path('app/public/upload/document/' . $documentType . '/' . $fileToUpdate);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                // Upload the new file
                $filenameWithExt = $newFile->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $newFile->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = storage_path('app/public/upload/document/' . $documentType);

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                $newFile->move($dir, $fileNameToStore);

                // Update the document field with the new file
                $claim->$updateField = $fileNameToStore;
            } else {
                // For multi-file document types, replace the file in the array
                $key = array_search($fileToUpdate, $existingFiles);
                if ($key !== false) {
                    // Remove the old file from storage
                    $oldFilePath = storage_path('app/public/upload/document/' . $documentType . '/' . $fileToUpdate);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }

                    // Upload the new file
                    $filenameWithExt = $newFile->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $newFile->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = storage_path('app/public/upload/document/' . $documentType);

                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    $newFile->move($dir, $fileNameToStore);

                    // Update the array with the new file
                    $existingFiles[$key] = $fileNameToStore;
                } else {
                    // For single file document types, or when file is not found in array, treat as new file
                    $filenameWithExt = $newFile->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $newFile->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = storage_path('app/public/upload/document/' . $documentType);

                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    $newFile->move($dir, $fileNameToStore);

                    // Add new file to array
                    $existingFiles[] = $fileNameToStore;
                }

                // Update the document field with the new files (as an array)
                $claim->$updateField = json_encode($existingFiles);
            }

            // Save the updated claim
            $claim->save();

            // Log the action
            $this->logClaimAction(
                $claim,
                'document_update',
                'Document updated via customer portal: ' . $documentType,
                null,
                ['document_type' => $documentType, 'old_file' => $fileToUpdate, 'new_file' => $fileNameToStore]
            );

            return response()->json([
                'message' => 'Document updated successfully',
                'new_file' => $fileNameToStore
            ]);
        } catch (\Exception $e) {
            Log::error('Document update error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred during document update'], 500);
        }
    }
    public function addDocument(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'claim_id' => 'required|exists:claims,id',
                'document_type' => 'required|in:aadhaar,rcbook,pan_card,tax_receipt,sales_invoice,dl,insurance,claimform,claimintimation,consentform,satisfactionvoucher,fir,number_plate,finalbill,paymentreceipt',
                'new_file' => 'required|mimes:jpg,jpeg,png,pdf,mp4,webm|max:10240', // 10MB max file size
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $claim = Claim::findOrFail($request->input('claim_id'));
            $documentType = $request->input('document_type');
            $newFile = $request->file('new_file');

            // Get the appropriate field based on document type
            $updateField = $this->getUpdateField($documentType);

            // Retrieve existing files (if any)
            $existingFiles = json_decode($claim->$updateField ?? '[]', true);

            // Upload the new file
            $filenameWithExt = $newFile->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $newFile->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir = storage_path('app/public/upload/document/' . $documentType);

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $newFile->move($dir, $fileNameToStore);

            // For single-file document types, store the new file directly
            if (in_array($documentType, ['insurance', 'claimform', 'claimintimation', 'satisfactionvoucher', 'consentform', 'fir','finalbill'])) {
                $claim->$updateField = $fileNameToStore;
            } else {
                // For multi-file document types, add the new file to the array
                $existingFiles[] = $fileNameToStore;
                $claim->$updateField = json_encode($existingFiles);
            }

            $claim->save();

            // Log the action
            $this->logClaimAction(
                $claim,
                'document_add',
                'Document added via customer portal: ' . $documentType,
                null,
                ['document_type' => $documentType, 'new_file' => $fileNameToStore]
            );

            return response()->json([
                'message' => 'Document added successfully',
                'new_file' => $fileNameToStore
            ]);
        } catch (\Exception $e) {
            Log::error('Document add error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred during document addition'], 500);
        }
    }
    public function deleteDocument(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'claim_id' => 'required|exists:claims,id',
                'document_type' => 'required|in:aadhaar,rcbook,pan_card,tax_receipt,sales_invoice,dl,insurance,claimform,claimintimation,consentform,satisfactionvoucher,fir,number_plate,finalbill,paymentreceipt',
                'file_to_delete' => 'required|string', // filename of the document to be deleted
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $claim = Claim::findOrFail($request->input('claim_id'));
            $documentType = $request->input('document_type');
            $fileToDelete = $request->input('file_to_delete');

            // Get the appropriate field based on document type
            $updateField = $this->getUpdateField($documentType);

            // Retrieve existing files
            $existingFiles = json_decode($claim->$updateField ?? '[]', true);

            // Remove the file from storage
            $filePath = storage_path('app/public/upload/document/' . $documentType . '/' . $fileToDelete);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // For single-file document types, set the field to null
            if (in_array($documentType, ['insurance', 'claimform', 'claimintimation', 'satisfactionvoucher', 'consentform', 'fir','number_plate','finalbill'])) {
                $claim->$updateField = null;
            } else {
                // For multi-file document types, remove the file from the array
                $existingFiles = array_filter($existingFiles, function($file) use ($fileToDelete) {
                    return $file !== $fileToDelete;
                });

                $claim->$updateField = json_encode(array_values($existingFiles));
            }

            $claim->save();

            // Log the action
            $this->logClaimAction(
                $claim,
                'document_delete',
                'Document deleted via customer portal: ' . $documentType,
                null,
                ['document_type' => $documentType, 'deleted_file' => $fileToDelete]
            );

            return response()->json([
                'message' => 'Document deleted successfully',
                'remaining_files' => $existingFiles
            ]);
        } catch (\Exception $e) {
            Log::error('Document delete error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred during document deletion'], 500);
        }
    }


}
