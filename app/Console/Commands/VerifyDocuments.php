<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Claim;
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use File;
use DB;
use App\Models\VehicleRegistration;
use Carbon\Carbon;
use App\Models\ClaimLog;
use Illuminate\Support\Facades\Config;

class VerifyDocuments extends Command
{
    protected $signature = 'verify:documents';
    protected $description = 'Verify documents like RC, DL, and Insurance in claims';

    protected $client;
    protected $ocrApiKey;
    protected $parser;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        $this->parser = new Parser();
        $this->ocrApiKey = Config::get('services.ocr.api_key');
    }

    public function handle()
    {
        Log::info('Cron job started: Verifying documents');

        try {
            Claim::where('status', 'documents_submitted')
                ->where('is_processed', false)
                ->chunk(10, function($claims) {
                    foreach ($claims as $claim) {
                        $this->processClaim($claim);
                    }
                });

            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error("Cron job failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function processClaim(Claim $claim)
    {
        try {
            Log::info("Processing claim ID: {$claim->claim_id}");
            $this->logClaimAction(
                $claim,
                'System',
                "Processing started for claim ID: {$claim->claim_id}",
                null,
                $claim->toArray()
            );
             // Fetch and store vehicle registration details first
        if (!$this->processVehicleRegistration($claim)) {
            return;
        }

            // Process photo files first
            $this->processPhotoFiles($claim);

            // Get document details
            $insuranceDetails = $this->parseInsuranceFile($claim);
            $rcDetails = $this->parseRcFiles($claim);

            // Validate documents
            if (!$this->validateDocuments($claim, $insuranceDetails, $rcDetails)) {
                return;
            }

            // Mark claim as under review
            $this->updateClaimStatus($claim, 'under_review', 'All documents are under review process');
            $claim->is_processed = true;
            $claim->save();
            $this->logClaimAction(
                $claim,
                'System',
                "Processing completed for claim ID: {$claim->claim_id}",
                null,
                $claim->toArray()
            );
        } catch (\Exception $e) {
            Log::error("Error processing claim {$claim->claim_id}: " . $e->getMessage());
            $this->updateClaimStatus($claim, 'rejected', 'Error during verification: ' . $e->getMessage());
        }
    }

    protected function validateDocuments($claim, $insuranceDetails, $rcDetails)
    {
        // Check for document mismatch
        if ($insuranceDetails['engine_no'] !== $rcDetails['engine_no'] || 
            $insuranceDetails['chassis_no'] !== $rcDetails['chassis_no']) {
            $this->updateClaimStatus($claim, 'documents_mismatched', 'Document Mismatched: Engine No. or Chassis No. mismatch');
            return false;
        }

        // Check license status
        if (!$this->isLicenseActive($claim)) {
            $this->updateClaimStatus($claim, 'rejected', 'License not active at the time of the accident');
            return false;
        }

        return true;
    }

    protected function processPhotoFiles($claim)
    {
        $photoFiles = json_decode($claim->photo_files, true);
        if (empty($photoFiles)) return;

        $damageResults = [];
        $processedImageFiles = [];

        foreach ($photoFiles as $file) {
            $photoFilePath = storage_path('app/public/upload/document/photos/' . $file['filename']);
            
            if (!file_exists($photoFilePath)) {
                Log::error("Photo file not found: {$photoFilePath}");
                continue;
            }

            try {
                $result = $this->analyzeDamage($photoFilePath, $file['filename']);
                if ($result) {
                    $damageResults = array_merge($damageResults, $result['damage_results']);
                    $processedImageFiles[] = $result['processed_image'];
                }
            } catch (\Exception $e) {
                Log::error("Error processing photo {$file['filename']}: " . $e->getMessage());
            }
        }

        $this->updateClaimWithPhotoResults($claim, $damageResults, $processedImageFiles);
    }

    protected function analyzeDamage($photoFilePath, $filename)
    {
        $response = Http::attach('image', file_get_contents($photoFilePath), $filename)
            ->post('https://combiapi-vace.onrender.com/analyze');

        if (!$response->successful()) {
            Log::error("Damage analysis failed for {$filename}: " . $response->body());
            return null;
        }

        $result = $response->json();
        $damageResults = $this->processDamageParts($result['damaged_parts']);
        $processedImage = null;

        if (!empty($result['processed_image_base64'])) {
            $processedImage = $this->saveProcessedImage($result['processed_image_base64'], $filename);
        }

        return [
            'damage_results' => $damageResults,
            'processed_image' => $processedImage
        ];
    }

    protected function updateClaimWithPhotoResults($claim, $damageResults, $processedImageFiles)
    {
        if (!empty($damageResults)) {
            $claim->damage_result = json_encode($damageResults);
        }

        if (!empty($processedImageFiles)) {
            $existingProcessedImages = json_decode($claim->processed_image_files, true) ?? [];
            $claim->processed_image_files = json_encode(array_merge($existingProcessedImages, $processedImageFiles));
        }

        $claim->save();
    }

   protected function parseInsuranceFile($claim)
    {
        $filePath = storage_path('app/public/upload/document/insurance/' . $claim->insurance_file);
        
        if (!file_exists($filePath)) {
            Log::error("Insurance file not found: {$filePath}");
            return ['engine_no' => null, 'chassis_no' => null];
        }
        
        try {
            // Create a temporary file path for the unrestricted PDF
            $tempFilePath = storage_path('app/public/upload/temp/' . uniqid('unrestricted_') . '.pdf');
            
            // Ensure temp directory exists
            if (!file_exists(dirname($tempFilePath))) {
                mkdir(dirname($tempFilePath), 0755, true);
            }
            
            // Remove restrictions using Ghostscript
            $this->removeFileRestrictions($filePath, $tempFilePath);
            
            // Extract text from the unrestricted PDF
            $text = $this->extractTextFromPdf($tempFilePath);
            
            // Store OCR result
            $this->storeOcrResult($claim, 'insurance', $filePath, $text);
            
            // Clean up temporary file
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            
            return $this->extractVehicleDetails($text);
        } catch (\Exception $e) {
            Log::error("Error parsing insurance file: " . $e->getMessage());
            return ['engine_no' => null, 'chassis_no' => null];
        }
    }

    /**
     * Remove restrictions from PDF file using Ghostscript
     *
     * @param string $inputPath Original PDF path
     * @param string $outputPath Path for unrestricted PDF
     * @throws \Exception
     */
    private function removeFileRestrictions($inputPath, $outputPath)
    {
        try {
            // Get absolute path to Ghostscript
            $gsPath = trim(shell_exec('which gs'));
            if (empty($gsPath)) {
                throw new \Exception("Ghostscript not found");
            }

            // Create command with absolute path to Ghostscript
            $command = sprintf(
                '%s -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -dPDFSETTINGS=/default -sOutputFile=%s %s 2>&1',
                escapeshellarg($gsPath),
                escapeshellarg($outputPath),
                escapeshellarg($inputPath)
            );

            // Create output directory if it doesn't exist
            $outputDir = dirname($outputPath);
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Execute command
            exec($command, $output, $returnCode);

            // Log output for debugging
            Log::debug("Using Ghostscript at: " . $gsPath);
            Log::debug("Command executed: " . $command);
            Log::debug("Output: " . implode("\n", $output));
            
            if ($returnCode !== 0) {
                throw new \Exception(implode("\n", $output));
            }

            return file_exists($outputPath) && filesize($outputPath) > 0;
        } catch (\Exception $e) {
            Log::error("Ghostscript error: " . $e->getMessage());
            throw $e;
        }
    }
    protected function parseRcFiles($claim)
    {
        $rcFiles = json_decode($claim->rcbook_files, true) ?? [];
        $result = ['engine_no' => null, 'chassis_no' => null];

        foreach ($rcFiles as $file) {
            $rcFilePath = storage_path('app/public/upload/document/rcbook/' . $file);
            
            if (!file_exists($rcFilePath)) {
                Log::error("RC file not found: {$rcFilePath}");
                continue;
            }

            $text = $this->performOCR($claim, $rcFilePath, 'rcbook');
            $details = $this->extractVehicleDetails($text);

            if ($details['engine_no'] && $details['chassis_no']) {
                return $details;
            }
        }

        return $result;
    }

    protected function isLicenseActive($claim)
    {
        $dlFiles = json_decode($claim->dl_files, true) ?? [];
        $lossDate = \Carbon\Carbon::createFromFormat('Y-m-d', $claim->loss_date);

        foreach ($dlFiles as $file) {
            $dlFilePath = storage_path('app/public/upload/document/dl/' . $file);
            
            if (!file_exists($dlFilePath)) {
                Log::error("DL file not found: {$dlFilePath}");
                continue;
            }

            $text = $this->performOCR($claim, $dlFilePath, 'dl');
            $expiryDate = $this->extractExpiryDate($text);

            if ($expiryDate) {
                return $expiryDate->greaterThanOrEqualTo($lossDate);
            }
        }

        return false;
    }

    protected function extractExpiryDate($text)
    {
        $patterns = [
            '/Expiry Date:\s*(\d{2}\/\d{2}\/\d{4})/',
            '/Valid Till:\s*(\d{2}\/\d{2}\/\d{4})/',
            '/To:\s*(\d{2}\/\d{2}\/\d{4})/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $matches[1]);
            }
        }

        return null;
    }

    protected function performOCR($claim, $filePath, $documentType)
    {
        $ocrResults = json_decode($claim->ocr_results, true) ?? [];

        if (isset($ocrResults[$documentType][$filePath]['text'])) {
            return $ocrResults[$documentType][$filePath]['text'];
        }

        try {
            $response = $this->client->request('POST', 'https://api.ocr.space/parse/image', [
                'headers' => ['apikey' => $this->ocrApiKey],
                'multipart' => [
                    ['name' => 'file', 'contents' => fopen($filePath, 'r')],
                    ['name' => 'language', 'contents' => 'eng'],
                    ['name' => 'isOverlayRequired', 'contents' => 'false'],
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            
            if (!isset($result['ParsedResults'][0]['ParsedText'])) {
                throw new \Exception("Invalid OCR response");
            }

            $text = $result['ParsedResults'][0]['ParsedText'];
            $this->storeOcrResult($claim, $documentType, $filePath, $text);

            return $text;
        } catch (\Exception $e) {
            Log::error("OCR failed for {$filePath}: " . $e->getMessage());
            throw $e;
        }
    }

    protected function storeOcrResult($claim, $documentType, $filePath, $text)
    {
        $ocrResults = json_decode($claim->ocr_results, true) ?? [];
        $ocrResults[$documentType] = $ocrResults[$documentType] ?? [];
        
        $ocrResults[$documentType][$filePath] = [
            'text' => $text,
            'timestamp' => now()->toDateTimeString()
        ];

        $claim->ocr_results = json_encode($ocrResults);
        $claim->save();
    }

    protected function extractTextFromPdf($filePath)
    {
        return $this->parser->parseFile($filePath)->getText();
    }

    protected function extractVehicleDetails($text)
    {
        preg_match('/Engine No:\s*(\w+)/', $text, $engineMatches);
        preg_match('/Chassis No:\s*(\w+)/', $text, $chassisMatches);

        return [
            'engine_no' => $engineMatches[1] ?? null,
            'chassis_no' => $chassisMatches[1] ?? null,
        ];
    }

    protected function updateClaimStatus($claim, $status, $notes)
    {
        $claim->status = $status;
        $claim->notes = $notes;
        $claim->save();
        Log::info("Claim ID {$claim->claim_id} updated to status: {$status} - {$notes}");
        $this->logClaimAction(
            $claim,
            'System',
            "Claim ID {$claim->claim_id} updated to status: {$status} - {$notes}",
            null,
            $claim->toArray()
        );
    }

    protected function processDamageParts($damagedParts)
    {
        // Remove duplicate entries based on the 'class' key
        $uniqueParts = [];
        foreach ($damagedParts as $part) {
            if (!isset($uniqueParts[$part['class']])) {
                $uniqueParts[$part['class']] = $part;
            }
        }

        return array_map(function($part) {
            $priceInfo = $this->getDamagePricing($part['class']);
            return [
                'class' => $part['class'],
                'score' => $part['score'],
                'severity' => $part['severity'],
                'price' => $priceInfo['price'] ?? 0,
                'material' => $priceInfo['material'] ?? '',
                'tax' => 0,
                'labour' => 0,
                'paint' => 0
            ];
        }, array_values($uniqueParts));
    }

    protected function getDamagePricing($detectedPart)
    {
        $mappedParts = [
            "Taillight" => "Taillight",
            "Windshield" => "Windshield Glass",
            "Side Mirror" => "Side Mirror",
            "Front door" => "Front Door",
            "Rear door" => "Rear Door",
            "Quarter panel" => "Quarter Pannel",
            "Rear Quarter panel" => "Quarter Pannel",
            "Wheel" => "Wheel",
            "Roof" => "Roof",
            "windows" => "Glass",
            "Rear windshield" => "Back Door Glass",
            "Rear bumper" => "Rear Bumper",
            "Trunk" => "Diggi / Back Door",
            "Hood" => "Hood/Bonnet",
            "Bumper" => "Front Bumper",
            "Headlight" => "Headlight",
            "Grill" => "Grill",
            "Destroyed" => null
        ];

        if (!isset($mappedParts[$detectedPart]) || !$mappedParts[$detectedPart]) {
            return [];
        }

        // Retrieve both price and material from the database
        if(isset($mappedParts[$detectedPart])){
        $partDetails = DB::table('parts')
            ->where('part', 'LIKE', "%{$mappedParts[$detectedPart]}%")
            ->select('rate', 'material') // Select both columns
            ->first(); // Use first() since you are expecting only one result
        }

        if ($partDetails) {
            return [
                'price' => $partDetails->rate ?? 0,
                'material' => $partDetails->material ?? ''
            ];
        }

        return [];
    }

    protected function saveProcessedImage($base64Image, $fileName)
    {
        $imageData = base64_decode($base64Image);
        $processedImageName = 'processed_' . pathinfo($fileName, PATHINFO_FILENAME) . '_' . Str::random(10) . '.jpg';
        $processedImagePath = storage_path('app/public/upload/processed_image/' . $processedImageName);

        if (!File::isDirectory(dirname($processedImagePath))) {
            File::makeDirectory(dirname($processedImagePath), 0755, true);
        }

        file_put_contents($processedImagePath, $imageData);
        Log::info("Processed image saved: {$processedImagePath}");

        return $processedImageName;
    }

    protected function processVehicleRegistration(Claim $claim)
    {
        if (!$claim->vehicle_number) {
            Log::error("No vehicle number found for claim ID: {$claim->claim_id}");
            $this->updateClaimStatus($claim, 'rejected', 'Vehicle registration number is missing');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.surepass.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://kyc-api.surepass.io/api/v1/rc/rc-full', [
                'id_number' => $claim->vehicle_number
            ]);

            if (!$response->successful()) {
                Log::error("Vehicle API request failed for claim ID {$claim->claim_id}: " . $response->body());
                $this->updateClaimStatus($claim, 'documents_pending', 'Failed to fetch vehicle registration details');
                return false;
            }

            $data = $response->json()['data'];

            // Create or update vehicle registration record
            VehicleRegistration::updateOrCreate(
                ['claim_id' => $claim->id],
                [
                    'rc_number' => $data['rc_number'],
                    'registration_date' => $this->parseDate($data['registration_date']),
                    'owner_name' => $data['owner_name'],
                    'owner_number' => $data['owner_number'],
                    'vehicle_category' => $data['vehicle_category'],
                    'vehicle_chasi_number' => $data['vehicle_chasi_number'],
                    'vehicle_engine_number' => $data['vehicle_engine_number'],
                    'maker_model' => $data['maker_model'],
                    'body_type' => $data['body_type'],
                    'fuel_type' => $data['fuel_type'],
                    'color' => $data['color'],
                    'financed' => $data['financed'],
                    'fit_up_to' => $this->parseDate($data['fit_up_to']),
                    'insurance_upto' => $this->parseDate($data['insurance_upto']),
                    'rc_status' => $data['rc_status'],
                    'blacklist_status' => $data['blacklist_status'],
                ]
            );

            return true;

        } catch (\Exception $e) {
            Log::error("Error processing vehicle registration for claim {$claim->claim_id}: " . $e->getMessage());
            $this->updateClaimStatus($claim, 'rejected', 'Error processing vehicle registration details');
            return false;
        }
    }

    protected function parseDate($dateString)
    {
        try {
            return $dateString ? Carbon::parse($dateString)->format('Y-m-d') : null;
        } catch (\Exception $e) {
            return null;
        }
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
}