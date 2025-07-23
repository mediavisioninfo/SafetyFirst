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
            //FEES BILL DATA
            $feesBillData = ProfessionalFee::whereIn('claim_id', $claims->pluck('id'))->get()->keyBy('claim_id');
            
            return view('claim.index', compact('claims','feesBillData','insuranceDetail','states'));
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
            // Send SMS if SMSCountry credentials are set
            $authKey = env('SMSCOUNTRY_AUTHKEY');
            $authToken = env('SMSCOUNTRY_AUTHTOKEN');
            $senderId = env('SMSCOUNTRY_SENDERID');

            $mobile = $request->mobile;

            if (substr($mobile, 0, 3) === '+91') {
                $mobile = substr($mobile, 3);
            } elseif (substr($mobile, 0, 1) === '0') {
                $mobile = substr($mobile, 1);
            }
            $mobile = '91' . $mobile;

            $auth = base64_encode("$authKey:$authToken");

            $data = [
                "Text" => "Dear ABC, To process your Car insurance claim -Claim No: $request->claim_id, please upload the required documents at: $uploadLink For help, call 080-62965696. Regards SafetyFirst" ,
                "Number" => $mobile,  // ✅ Correct key
                "SenderId" => $senderId,
                "TemplateId" => "1707174703017862364",
                "Is_Unicode" => false
            ];

            $response = Http::withHeaders([
                'Authorization' => "Basic $auth",
                'Content-Type' => 'application/json'
            ])->post("https://restapi.smscountry.com/v0.1/Accounts/$authKey/SMSes", $data);

            $responseData = $response->json();

            if ($responseData['Success']) {
                \Log::info("SMS successfully queued", [
                    'uuid' => $responseData['MessageUUID'],
                    'mobile' => $mobile
                ]);
            }else {
                \Log::error('SMSCountry failed', ['response' => $response->body()]);
                // Optionally, you can also return an error message or handle failures
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
            $insurance = $claim->insurances;
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
            }
    
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

            dd($otp);
            // Send OTP via SMS
            $mobile = $claim->mobile;
            if (substr($mobile, 0, 3) === '+91') $mobile = substr($mobile, 3);
            if (substr($mobile, 0, 1) === '0') $mobile = substr($mobile, 1);
            $mobile = '91' . $mobile;

            $authKey = env('SMSCOUNTRY_AUTHKEY');
            $authToken = env('SMSCOUNTRY_AUTHTOKEN');
            $senderId = env('SMSCOUNTRY_SENDERID');
            $auth = base64_encode("$authKey:$authToken");

            $message = "Dear Customer, Your One-Time Password (OTP) to access the claim document upload portal is $otp. This OTP is valid for 10 minutes from the time of issuance. For your security, please do not share this code with anyone. Regards SafetyFirst";

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

            return view('claims.enter_otp', compact('claimId', 'encryptedId'));

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
            return redirect()->route('claim.upload.documents', ['id' => Crypt::encrypt($claimId)]);
        } else {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

    public function uploadForm($id)
    {
        //07-feb-2025 add by tanuja
        $claimId = decrypt($id);

        if (!Session::get("otp_verified_$claimId")) {
            return redirect()->route('claim.upload.otp', ['id' => $id])
                            ->with('error', 'Please verify OTP first.');
        }

        $claimData = Claim::select('*')->where('id',$claimId)->get()->toArray();
        $user = \Auth::user();
        return view('claim.upload', compact('claimId','user','claimData'));
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
            }else if($documentType === 'aadhaar'){
                $uploadedFiles = $this->handleFileUpload($request->file('files'), $documentType, $claim->id);

                // ✅ Run OCR on the first Aadhaar image
                $aadhaarImagePath = storage_path('app/public/upload/document/claim-' . $claim->id . '/aadhaar/' . $uploadedFiles[0]);
                $ocrText = (new TesseractOCR($aadhaarImagePath))->run();

                // dd($ocrText);
                // ✅ Extract Aadhaar number (format: 1234 5678 9123)
                preg_match('/\b[0-9]{4}\s[0-9]{4}\s[0-9]{4}\b/', $ocrText, $aadhaarNumberMatches);
                $aadhaarNumber = $aadhaarNumberMatches[0] ?? null;

                // ✅ Extract Aadhaar name (basic assumption: line after "Name" or similar)
                // preg_match('/(?<=Name|NAME|Nmae)[^\n]{3,}/i', $ocrText, $nameMatches);
                // $aadhaarName = $nameMatches[0] ?? null;
                $lines = explode("\n", $ocrText);
                $aadhaarName = null;

                // Clean lines: remove empty, trim space
                $lines = array_values(array_filter(array_map('trim', $lines)));

                foreach ($lines as $index => $line) {
                    // Look for a line containing DOB (in English or Hindi)
                    if (stripos($line, 'DOB') !== false && isset($lines[$index - 1])) {
                        // Check if the previous line looks like a name (alphabetic)
                        $potentialName = $lines[$index - 1];

                        // Skip Hindi lines or lines with special chars
                        if (preg_match('/^[a-zA-Z\s.]+$/', $potentialName)) {
                            $aadhaarName = trim($potentialName);
                            break;
                        }

                        // Fallback: look further up
                        for ($i = $index - 2; $i >= 0; $i--) {
                            if (preg_match('/^[a-zA-Z\s.]+$/', $lines[$i])) {
                                $aadhaarName = trim($lines[$i]);
                                break 2;
                            }
                        }
                    }
                }

                // Fallback if still not found
                if (!$aadhaarName) {
                    preg_match('/^[A-Z][a-z]+\s+[A-Z][a-z]+/', $ocrText, $nameMatches); // Basic two-word name
                    $aadhaarName = $nameMatches[0] ?? null;
                }

                // ✅ Save to claim table if both are found
                if ($aadhaarNumber || $aadhaarName) {
                    $claim->aadhaar_number = $aadhaarNumber;
                    $claim->aadhaar_name = $aadhaarName;
                    $claim->save();
                }
            }
            else {
                $uploadedFiles = $this->handleFileUpload($request->file('files'), $documentType, $claim->id);
            }
    
            $this->updateClaimWithUploadedFiles($claim, $documentType, $uploadedFiles);
            
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
            'files' => 'required',
            'files.*' => 'mimes:jpg,jpeg,png,pdf,mp4,webm|max:10240', // 1MB max file size for FIR
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $claim = Claim::findOrFail($request->input('claim_id'));
        $firFile = $request->file('files');
        
        // Handle FIR file upload
        $firFilename = $this->handleSingleFileUpload($firFile, 'fir');
        
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
        // $storagePath = storage_path('upload/document/');
        //07-feb-2025 add by tanuja
        $storagePath = storage_path('app/public/upload/document/');
        foreach ($files as $file) {
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir = $storagePath . "/claim-{$claimId}/" . $documentType;
            
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filePath = $dir . '/' . $fileNameToStore;
            $file->move($dir, $fileNameToStore);
            $uploadedFiles[] = $fileNameToStore;
        }
        return $uploadedFiles;
    }

    private function handleSingleFileUpload($files, $documentType)
    {
        // $dir = storage_path('upload/document/') . '/' . $documentType;
        //07-feb-2025 add by tanuja
        $claimId = $request->input('claim_id');
        $dir = storage_path('app/public/upload/document/claim-{$claimId}') . '/' . $documentType;
        foreach ($files as $file) {

        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $file->move($dir, $fileNameToStore);
        }
        return $fileNameToStore;
    }

    /*private function handlePhotoUploadWithGeotag($files, $geotags, $captureTimes)
    {
        $uploadedFiles = [];
        // $storagePath = storage_path('upload/document/photos');
        //07-feb-2025 add by tanuja
        $storagePath = storage_path('app/public/upload/document/photos');
        
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        foreach ($files as $index => $file) {
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            
            $file->move($storagePath, $fileNameToStore);
            
            // Validate geotag JSON
            $geotagJson = $geotags[$index] ?? null;
            $geotag = null;
            if (!is_null($geotagJson) && $geotagJson !== "null") {
                $geotag = json_decode($geotagJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON in geotag at index $index: " . $geotagJson);
                }
            }

            $uploadedFiles[] = [
                'filename' => $fileNameToStore,
                'geotag' => $geotag,
                'captureTime' =>  $captureTimes[$index] ?? null
            ];
        }
        
        return $uploadedFiles;
    }*/


    // private function handlePhotoUploadWithGeotag($files, $geotags, $captureTimes)
    // {
    //     $uploadedFiles = [];
    //     $storagePath  = storage_path('app/public/upload/document/photos');
    //     $pdfSavePath  = storage_path('app/public/upload/document/photos-pdf');

    //     if (!file_exists($storagePath)) {
    //         mkdir($storagePath, 0777, true);
    //     }

    //     if (!file_exists($pdfSavePath)) {
    //         mkdir($pdfSavePath, 0777, true);
    //     }

    //     // Upload photos and build data URI
    //     foreach ($files as $i => $file) {
    //         $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //         $ext = $file->getClientOriginalExtension();
    //         $storedFileName = $filename . '_' . time() . '.' . $ext;
            
    //         $file->move($storagePath, $storedFileName);

    //         $fullPath = $storagePath . '/' . $storedFileName;
    //         $mime = mime_content_type($fullPath);
    //         $base64 = base64_encode(file_get_contents($fullPath));
    //         $dataUri = "data:$mime;base64,$base64";

    //         $geotagJson = $geotags[$i] ?? null;
    //         $geotag = null;
    //         if (!is_null($geotagJson) && $geotagJson !== "null") {
    //             $geotag = json_decode($geotagJson, true);
    //             if (json_last_error() !== JSON_ERROR_NONE) {
    //                 throw new \Exception("Invalid JSON in geotag at index $i");
    //             }
    //         }

    //         $uploadedFiles[] = [
    //             'filename' => $storedFileName,
    //             'dataUri' => $dataUri,
    //             'captureTime' => $captureTimes[$i] ?? null,
    //             'geotag' => $geotag,
    //         ];
    //     }
    //     dd($uploadedFiles);

    //     // Create PDF with 6 photos per page
    //     // $pdf = Pdf::loadView('pdf.photo_grid', ['photos' => $uploadedFiles]);
    //     // $pdf->setPaper('a4', 'portrait'); // Optional: set paper size

    //     // $pdfFileName = 'photos_pdf_' . time() . '.pdf';
    //     // $pdf->save($pdfSavePath . '/' . $pdfFileName);

    //     return $uploadedFiles;
    // }

    public function showImage($claimHash, $folderHash, $filename)
    {
        $photoFolders = [
            'vehicle_photos',
            'under_repair_photos',
            'final_photos',
        ];

        foreach ($photoFolders as $folder) {

            $path = storage_path("app/public/upload/document/claim-{$claimHash}/photos/{$folder}/{$filename}");

            if (file_exists($path)) {
                try {
                    $encryptedContent = file_get_contents($path);
                    $decryptedContent = Crypt::decrypt($encryptedContent);
                } catch (\Exception $e) {
                    abort(403, 'Unable to decrypt image.');
                }

                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $mime = match(strtolower($extension)) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png'        => 'image/png',
                    'gif'        => 'image/gif',
                    default      => 'application/octet-stream',
                };

                return response($decryptedContent)->header('Content-Type', $mime);
            }
        }

        abort(404); // Not found in any folder
    }

    private function handlePhotoUploadWithGeotag($files, $geotags, $captureTimes, $claimId, $photoType = 'vehicle')
    {
        $uploadedFiles = [];

        // Map folder names
        $photoTypeMap = [
            'vehicle'       => 'vehicle_photos',
            'under_repair'  => 'under_repair_photos',
            'final'         => 'final_photos',
        ];

        $claimHash    = md5($claimId);

        $photoFolder = $photoTypeMap[$photoType] ?? 'vehicle_photos'; // default fallback
        $storagePath = storage_path("app/public/upload/document/claim-{$claimHash}/photos/{$photoFolder}");
        $pdfPath     = storage_path('app/photos/pdf'); // Store all PDFs in photos/pdf


        // Ensure directories exist
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0777, true);
        }

        // Encrypt and store each image file
        foreach ($files as $i => $file) {
            $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext      = $file->getClientOriginalExtension();
            $stored   = md5($origName . time() . rand()) . '.' . $ext;

            // Encrypt file content
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

        // Create PDF from decrypted content
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

                    unlink($decryptedTempPath); // cleanup
                } catch (\Exception $e) {
                    continue; // skip invalid or unreadable images
                }

                // Move to next row/column
                if ((($idx + 1) % 2) === 0) {
                    $x = 10;
                    $y += $cellH + 10;
                } else {
                    $x += $cellW + 10;
                }
            }
        }

        // Save the encrypted photo PDF
        $pdfFileName = $photoType . '_photos_pdf_' . time() . '.pdf';
        $pdf->Output('F', $pdfPath . '/' . $pdfFileName);

        return $uploadedFiles;
    }

    // private function handlePhotoUploadWithGeotag($files, $geotags, $captureTimes)
    // {
    //     $uploadedFiles = [];
    //     $storagePath = storage_path('app/public/upload/document/photos');

    //     if (!file_exists($storagePath)) {
    //         mkdir($storagePath, 0777, true);
    //     }

    //     foreach ($files as $index => $file) {
    //         $filenameWithExt = $file->getClientOriginalName();
    //         $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    //         $extension = $file->getClientOriginalExtension();
    //         $fileNameToStore = $filename . '_' . time() . '.' . $extension;

    //         $file->move($storagePath, $fileNameToStore);

    //         $geotagJson = $geotags[$index] ?? null;
    //         $geotag = null;
    //         if (!is_null($geotagJson) && $geotagJson !== "null") {
    //             $geotag = json_decode($geotagJson, true);
    //             if (json_last_error() !== JSON_ERROR_NONE) {
    //                 throw new \Exception("Invalid JSON in geotag at index $index: " . $geotagJson);
    //             }
    //         }

    //         $uploadedFiles[] = [
    //             'filename' => $fileNameToStore,
    //             'geotag' => $geotag,
    //             'captureTime' =>  $captureTimes[$index] ?? null
    //         ];
    //     }

    //     // PDF generation
    //     $pdfPath = storage_path('app/public/upload/document/photos-pdf');
    //     if (!file_exists($pdfPath)) {
    //         mkdir($pdfPath, 0777, true);
    //     }

    //     $pdf = new \FPDF();
    //     $pdf->SetAutoPageBreak(true, 10);
    //     $pdf->SetMargins(10, 10, 10);

    //     $imagesPerPage = 6;
    //     $imgWidth = 60;
    //     $imgHeight = 60;

    //     foreach (array_chunk($uploadedFiles, $imagesPerPage) as $chunk) {
    //         $pdf->AddPage();
    //         $x = 10;
    //         $y = 10;

    //         foreach ($chunk as $i => $photo) {
    //             $imagePath = $storagePath . '/' . $photo['filename'];
    //             $pdf->Image($imagePath, $x, $y, $imgWidth, $imgHeight);

    //             $x += $imgWidth + 10;
    //             if (($i + 1) % 2 === 0) {
    //                 $x = 10;
    //                 $y += $imgHeight + 10;
    //             }
    //         }
    //     }

    //     $pdfFilename = 'photos_pdf_' . time() . '.pdf';
    //     $pdf->Output('F', $pdfPath . '/' . $pdfFilename);

    //     return $uploadedFiles;
    // }


    // its working
        /*private function handlePhotoUploadWithGeotag($files, $geotags, $captureTimes)
        {
            $uploadedFiles = [];
            $storagePath = storage_path('app/public/upload/document/photos');

            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            foreach ($files as $index => $file) {
                $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $file->move($storagePath, $fileNameToStore);

                // Validate geotag JSON
                $geotagJson = $geotags[$index] ?? null;
                $geotag = null;
                if (!is_null($geotagJson) && $geotagJson !== "null") {
                    $geotag = json_decode($geotagJson, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception("Invalid JSON in geotag at index $index: " . $geotagJson);
                    }
                }

                $uploadedFiles[] = [
                    'filename' => $fileNameToStore,
                    'geotag' => $geotag,
                    'captureTime' =>  $captureTimes[$index] ?? null
                ];
            }

            // === Generate PDF after upload ===
            $pdfSavePath = storage_path('app/public/upload/document/photos-pdf');
            if (!file_exists($pdfSavePath)) {
                mkdir($pdfSavePath, 0777, true);
            }

            $pdf = Pdf::loadView('pdf.photo_gallery', ['photos' => $uploadedFiles]);

            $pdfFileName = 'photos_pdf_' . time() . '.pdf';
            $pdf->save($pdfSavePath . '/' . $pdfFileName);

            // Optionally add the pdf path to returned array
            // return [
            //     'uploaded_photos' => $uploadedFiles,
            //     'pdf_file' => 'upload/document/' . $pdfFileName
            // ];
             return $uploadedFiles;

        }*/


    /*private function handlePhotoUploadWithGeotag($files, $geotags, $captureTimes)
    {
        // dd("hhhh");
        $uploadedFiles = [];
        $imagePathsForPdf = [];

        $storagePath = storage_path('app/public/upload/document/photos');

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        foreach ($files as $index => $file) {
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            // ✅ Resize & compress image using Intervention
            $image = Image::make($file)
                ->resize(1024, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($extension, 75);

            $image->save($storagePath . '/' . $fileNameToStore);

            // ✅ Add path for PDF generation
           $imagePathsForPdf[] = public_path('storage/upload/document/photos/' . $fileNameToStore);

            // dd($imagePathsForPdf[]);
            // ✅ Handle geotag
            $geotagJson = $geotags[$index] ?? null;
            $geotag = null;
            if (!is_null($geotagJson) && $geotagJson !== "null") {
                $geotag = json_decode($geotagJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON in geotag at index $index: " . $geotagJson);
                }
            }

            $uploadedFiles[] = [
                'filename' => $fileNameToStore,
                'geotag' => $geotag,
                'captureTime' => $captureTimes[$index] ?? null
            ];
        }

        // ✅ Generate PDF from all uploaded images
        $pdf = PDF::loadView('pdf.photo_summary', ['images' => $imagePathsForPdf]);
        $pdfPath = storage_path('app/public/upload/document/photo_summary.pdf');
        $pdf->save($pdfPath);

        return [
            'uploadedFiles' => $uploadedFiles,
            'pdf_path' => 'storage/upload/document/photo_summary.pdf'
        ];
    }*/






    private function handleNumberPlateUploadWithGeotag($files, $geotags, $captureTimes, $claimId)
    {
        $uploadedFiles = [];
        // $storagePath = storage_path('upload/document/number_plate');
        //07-feb-2025 add by tanuja
        $storagePath = storage_path("app/public/upload/document/claim-{$claimId}/number_plate");
        
        // Create the storage path if it doesn't exist
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        // Get the original filename and extension
        $filenameWithExt = $files->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $files->getClientOriginalExtension();
        
        // Create a unique filename using current time
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
        
        // dd($$fileNameToStore);
        // Move the uploaded file to the designated storage path
        $files->move($storagePath, $fileNameToStore);
        $geotag = json_decode($geotags, true);

        // Prepare the uploaded file data including the filename, geotag, and capture time
        $uploadedFiles[] = [
            'filename' => $fileNameToStore,
            'geotag' => $geotag,
            'captureTime' => $captureTimes
        ];
        // Call the OCR API to extract text from the image
        $ocrText = $this->extractTextFromImage($storagePath . '/' . $fileNameToStore);
        
        // Extract the vehicle number plate
        $vehicleNumber = $this->extractVehicleNumber($ocrText);
    
        // Optionally, add the extracted vehicle number to the uploaded file data
        $uploadedFiles[0]['vehicleNumber'] = $vehicleNumber;
    
        // Return the array of uploaded files with metadata
        // dd($uploadedFiles);
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
        if (!empty($claim->aadhaar_files)) {
            $this->mergeImagesIntoPDF($mpdf, json_decode($claim->aadhaar_files), 'aadhaar', 'Aadhaar');
        }
        if (!empty($claim->rcbook_files)) {
            $this->mergeImagesIntoPDF($mpdf, json_decode($claim->rcbook_files), 'rcbook', 'RC-Book');
        }
        if (!empty($claim->dl_files)) {
            $this->mergeImagesIntoPDF($mpdf, json_decode($claim->dl_files), 'dl', 'Driving License');
        }
    if (!empty($claim->photo_files)) {
            $photoFiles = array_map(fn($file) => $file['filename'], json_decode($claim->photo_files, true) ?: []);
            // dd($photoFiles);
            $this->mergeImagesIntoPDF($mpdf, $photoFiles, 'photos', 'Photos');
        }

        if (!empty($claim->processed_image_files)) {
            $this->mergeImagesIntoPDF($mpdf, json_decode($claim->processed_image_files), 'processed_image', 'Processed Images', 20);
        }

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
        
        if (!empty($claim->claim_form_file)) {
            $this->addPdfFileToList($pdfFiles, $claim->claim_form_file, 'claimform');
        }
        if (!empty($claim->claim_intimation_file)) {
            $this->addPdfFileToList($pdfFiles, $claim->claim_intimation_file, 'claimintimation');
        }
        if (!empty($claim->satisfaction_voucher_file)) {
            $this->addPdfFileToList($pdfFiles, $claim->satisfaction_voucher_file, 'satisfactionvoucher');
        }
        if (!empty($claim->consent_form_file)) {
            $this->addPdfFileToList($pdfFiles, $claim->consent_form_file, 'consentform');
        }
        if (!empty($claim->insurance_file)) {
            $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'insurance');
        }

        // Generate a unique output file name
        $mergedPdfPath = storage_path("temp/claim_report_{$claimId}_{$uniqueId}.pdf");
        $this->mergePdfFilesWithGhostscript($pdfFiles, $mergedPdfPath);


        // Return the final merged PDF
        return response()->download($mergedPdfPath);
    }

    private function addPdfFileToList(&$pdfFiles, $fileName, $folder)
    {
        if ($fileName) {
            $filePath = storage_path("app/public/upload/document/claim-749/{$folder}/{$fileName}");
            // dd($filePath);
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
        // Decode the stored JSON
        $photos = json_decode($claim->photo_files, true);

        // where the files live on disk:
        $storagePath = storage_path('app/public/upload/document/photos');

        // build the PDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(10, 10, 10);

        $imagesPerPage = 6;
        $cellW = 90;   // two across fits into 210mm minus margins
        $cellH = 60;   // three rows fits into 297mm minus margins

        foreach (array_chunk($photos, $imagesPerPage) as $chunk) {
            $pdf->AddPage();
            $x = 10;
            $y = 10;

            foreach ($chunk as $idx => $photo) {
                $fullPath = $storagePath . '/' . $photo['filename'];
                if (!file_exists($fullPath)) {
                    continue;
                }

                // get original px dimensions
                list($pxW, $pxH) = getimagesize($fullPath);
                // convert to mm (assuming 96dpi)
                $origW = $pxW * 25.4 / 96;
                $origH = $pxH * 25.4 / 96;
                // scale to fit cell
                $scale = min($cellW / $origW, $cellH / $origH);
                $drawW = $origW * $scale;
                $drawH = $origH * $scale;
                // center in cell
                $offX = $x + ($cellW - $drawW) / 2;
                $offY = $y + ($cellH - $drawH) / 2;
                // draw
                $pdf->Image($fullPath, $offX, $offY, $drawW, $drawH);

                // advance
                if ((($idx + 1) % 2) === 0) {
                    $x = 10;
                    $y += $cellH + 10;
                } else {
                    $x += $cellW + 10;
                }
            }
        }

        $filename = "claim_{$claim->id}_photos_" . now()->format('Ymd_His') . ".pdf";

        // send it as a download
        return response(
            $pdf->Output('S', $filename),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }

// private function mergeImagesIntoPDF($mpdf, $files, $folder, $label, $maxImages = 2, $includeMetadata = false)
// {
//     if (!empty($files)) {
//         $imageCounter = 0;
//         foreach ($files as $file) {
//             if ($imageCounter % 2 == 0) {
//                 // Add a new page every two images
//                 $mpdf->AddPage();
//             }

//             // Set the path for the current image file
//             $imagePath = ($folder == 'processed_image') 
//                 ? storage_path("upload/{$folder}/{$file}")
//                 : storage_path("upload/document/{$folder}/" . ($includeMetadata ? $file['filename'] : $file));
                
//             // dd($imagePath);
                
//             if (file_exists($imagePath)) {
//                 // Get the image's original size
//                 list($width, $height) = getimagesize($imagePath);

//                 // A4 page size in mm
//                 $pageWidth = 210;  // A4 width
//                 $pageHeight = 297; // A4 height

//                 // For a single image, auto-fit to the page while maintaining aspect ratio
//                 if (count($files) == 1) {
//                     // Calculate the scale ratio based on the page size and image dimensions
//                     $widthRatio = $pageWidth / $width;
//                     $heightRatio = $pageHeight / $height;

//                     // The scale ratio to fit the image completely within the page dimensions
//                     $scaleRatio = min($widthRatio, $heightRatio);

//                     // Calculate the new width and height
//                     $newWidth = $width * $scaleRatio;
//                     $newHeight = $height * $scaleRatio;

//                     // Center the image on the page
//                     $x = ($pageWidth - $newWidth) / 2; // Center horizontally
//                     $y = ($pageHeight - $newHeight) / 2; // Center vertically

//                     // Add the image to the PDF
//                     $mpdf->Image($imagePath, $x, $y, $newWidth, $newHeight, 'jpg', '', true, false);
//                 } else {
//                     // For multiple images, fit within the defined dimensions (90mm as before)
//                     $maxWidth = 90;
//                     $maxHeight = 90;

//                     // Calculate the scale ratio for the multiple image case
//                     $widthRatio = $maxWidth / $width;
//                     $heightRatio = $maxHeight / $height;
//                     $scaleRatio = min($widthRatio, $heightRatio);

//                     $newWidth = $width * $scaleRatio;
//                     $newHeight = $height * $scaleRatio;

//                     // Calculate position (left/right) for each image
//                     $x = ($imageCounter % 2 == 0) ? 10 : 110;
//                     $y = 50;

//                     // Add the image to the PDF
//                     $mpdf->Image($imagePath, $x, $y, $newWidth, $newHeight, 'jpg', '', true, false);
//                 }

//                 // Add metadata if available
//                 if ($includeMetadata && isset($file['captureTime']) && isset($file['geotag'])) {
//                     $mpdf->SetFont('', '', 8);
//                     $mpdf->SetXY($x, $y + $newHeight); // Adjust y-position based on new image height
//                     $captureTime = \Carbon\Carbon::parse($file['captureTime'])->format('Y-m-d H:i:s');
//                     $latitude = $file['geotag']['latitude'] ?? 'N/A';
//                     $longitude = $file['geotag']['longitude'] ?? 'N/A';
//                     $mpdf->WriteCell($newWidth, 5, "Capture Time: {$captureTime}", 0, 1, 'L');
//                     $mpdf->SetXY($x, $y + $newHeight + 5);
//                     $mpdf->WriteCell($newWidth, 5, "Lat: {$latitude}, Long: {$longitude}", 0, 1, 'L');
//                 }
//             }

//             $imageCounter++;

//             // Limit the number of images to the specified maximum
//             if ($imageCounter >= $maxImages) {
//                 break;
//             }
//         }
//     }
// }

    // private function mergeImagesIntoPDF($mpdf, $files, $folder, $label, $maxImages= 6, $includeMetadata = false)
    // {
    //     if (empty($files)) {
    //         return "No files provided.";
    //     }

    //     $imagesAdded = 0;
    //     $imagesPerPage = $maxImages;

    //     // Handle photos folder with 3x2 layout
    //     if ($folder === 'photos') {
    //         $cols = 3;
    //         $rows = 2;
    //         $cellW = 60;    // mm
    //         $cellH = 90;    // mm
    //         $paddingX = 10; // horizontal spacing
    //         $paddingY = 20; // vertical spacing

    //         // Process 6 images per page
    //         foreach (array_chunk($files, $imagesPerPage) as $pageImages) {
    //             $mpdf->AddPage();
    //             $mpdf->WriteHTML("<h3 style='text-align:center;'>{$label}</h3>");

    //             $row = 0;
    //             $col = 0;

    //             foreach ($pageImages as $file) {
    //                 $imagePath = ($folder == 'processed_image')
    //                     ? public_path("storage/upload/{$folder}/{$file}")
    //                     : public_path("storage/upload/document/{$folder}/" . ($includeMetadata ? $file['filename'] : $file));

    //                 if (file_exists($imagePath)) {
    //                     list($originalWidth, $originalHeight) = getimagesize($imagePath);

    //                     // Calculate scale ratio
    //                     $scaleRatio = min($cellW / $originalWidth, $cellH / $originalHeight);

    //                     $newWidth = $originalWidth * $scaleRatio;
    //                     $newHeight = $originalHeight * $scaleRatio;

    //                     // Calculate position
    //                     $x = $paddingX + $col * ($cellW + $paddingX);
    //                     $y = $paddingY + $row * ($cellH + $paddingY);

    //                     $mpdf->Image($imagePath, $x, $y, $newWidth, $newHeight, 'jpg', '', true, false);

    //                     $imagesAdded++;

    //                     $col++;
    //                     if ($col >= $cols) {
    //                         $col = 0;
    //                         $row++;
    //                     }
    //                 }
    //             }
    //         }

    //     } else {
    //         // Other documents: Full-page images
    //         foreach ($files as $file) {
    //             $filename = $includeMetadata ? $file['filename'] : $file;

    //             $imagePath = ($folder === 'processed_image')
    //                 ? public_path("storage/upload/{$folder}/{$filename}")
    //                 : public_path("storage/upload/document/{$folder}/{$filename}");

    //             if (!file_exists($imagePath)) {
    //                 continue;
    //             }

    //             $mpdf->AddPage();
    //             $mpdf->WriteHTML("<h3 style='text-align:center;'>$label</h3>");

    //             list($w, $h) = getimagesize($imagePath);
    //             $pageW = 190; // 210 - 20mm margin
    //             $pageH = 277; // 297 - 20mm margin

    //             $scale = min($pageW / $w, $pageH / $h);
    //             $newW = $w * $scale;
    //             $newH = $h * $scale;

    //             $x = (210 - $newW) / 2;
    //             $y = (297 - $newH) / 2;

    //             $mpdf->Image($imagePath, $x, $y, $newW, $newH, '', '', true, false);
    //             $imagesAdded++;
    //         }
    //     }

    //     return $imagesAdded > 0 ? "Images added successfully: $imagesAdded" : "No valid images found.";
    // }


    private function mergeImagesIntoPDF($mpdf, $files, $folder, $label, $maxImages = 6, $includeMetadata = false)
    {
        if (empty($files)) {
            return "No files provided.";
        }

        $imagesAdded = 0;

        // For 'photos' folder: 6 images per page, 2 per row
        if ($folder === 'photos') {
            $cols    = 2;    // 2 images per row
            $rows    = 3;    // 3 rows per page
            $cellW   = 90;   // Image width
            $cellH   = 65;   // Image height
            $gapX    = 10;   // Horizontal gap
            $gapY    = 10;   // Vertical gap
            $startX  = 15;   // Left margin
            $startY  = 25;   // Top margin

            foreach (array_chunk($files, $maxImages) as $pageImages) {
                $mpdf->AddPage();
                $mpdf->WriteHTML("<h3 style='text-align:center; margin-bottom:5mm;'>{$label}</h3>");

                $row = 0;
                $col = 0;

                foreach ($pageImages as $file) {
                    $filename = $includeMetadata ? $file['filename'] : $file;
                    $imagePath = public_path("storage/upload/document/{$folder}/" . $filename);

                    if (!file_exists($imagePath)) {
                        continue;
                    }

                    $x = $startX + $col * ($cellW + $gapX);
                    $y = $startY + $row * ($cellH + $gapY);

                    $mpdf->Image($imagePath, $x, $y, $cellW, $cellH, '', '', true, false);
                    $imagesAdded++;

                    $col++;
                    if ($col === $cols) {
                        $col = 0;
                        $row++;
                    }
                }
            }

        } else {
            // For other folders: one image per page
            foreach ($files as $file) {
                $filename = $includeMetadata ? $file['filename'] : $file;
                $imagePath = ($folder === 'processed_image')
                    ? public_path("storage/upload/processed_image/{$filename}")
                    : public_path("storage/upload/document/claim-749/{$folder}/{$filename}");

                if (!file_exists($imagePath)) {
                    continue;
                }

                $mpdf->AddPage();
                $mpdf->WriteHTML("<h3 style='text-align:center; margin-bottom:5mm;'>{$label}</h3>");

                list($w, $h) = getimagesize($imagePath);
                $maxW  = 190;  // A4 width minus margins
                $maxH  = 277;  // A4 height minus margins
                $scale = min($maxW / $w, $maxH / $h);
                $newW  = $w * $scale;
                $newH  = $h * $scale;
                $x     = (210 - $newW) / 2;
                $y     = (297 - $newH) / 2;

                $mpdf->Image($imagePath, $x, $y, $newW, $newH, '', '', true, false);
                $imagesAdded++;
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
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->aadhaar_files), 'aadhaar', 'Aadhaar');
        
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->rcbook_files), 'rcbook', 'RC-Book');
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->dl_files), 'dl', 'Driving License');
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->photo_files, true), 'photos', 'Photos', 6, true);
        // $this->mergeImagesIntoPDF($mpdf, json_decode($claim->photo_files, true), 'photos', 'Photos', 20, true);
        $this->mergeImagesIntoPDF($mpdf, json_decode($claim->processed_image_files), 'processed_image', 'Processed Images', 20);

        // Save the main report to a unique temporary file
        $uniqueId = uniqid();
        $mainReportPath = storage_path("temp/main_report_{$uniqueId}.pdf");
        $mpdf->Output($mainReportPath, \Mpdf\Output\Destination::FILE);

        // List of PDF files to merge
        $pdfFiles = [$mainReportPath];
        $this->addPdfFileToList($pdfFiles, $claim->claim_form_file, 'claimform');
        $this->addPdfFileToList($pdfFiles, $claim->claim_intimation_file, 'claimintimation');
        $this->addPdfFileToList($pdfFiles, $claim->satisfaction_voucher_file, 'satisfactionvoucher');
        $this->addPdfFileToList($pdfFiles, $claim->consent_form_file, 'consentform');
        $this->addPdfFileToList($pdfFiles, $claim->insurance_file, 'insurance');

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
            }

            if ($vehicle->vehicle_engine_number !== $insurance->engine_no) {
                $mismatches[] = 'Engine Number does not match.';
            }

            // Check if the insurance is active at the time of the loss date
            $lossDate = $claim->loss_date;

            // Ensure that the insurance dates are valid
            if ($insurance->insurance_start_date > $lossDate || $insurance->insurance_expiry_date < $lossDate) {
                $mismatches[] = 'Insurance is not active at the time of the loss date.';
            }

            // Check if the vehicle license is valid at the time of the loss date
            $licenseValidityDate = $license->validity_date;

            if ($licenseValidityDate < $lossDate) {
                $mismatches[] = 'Vehicle license has expired before the loss date.';
            }

        } else {
            $mismatches[] = 'Vehicle, Insurance, License, or Claim data is missing.';
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
