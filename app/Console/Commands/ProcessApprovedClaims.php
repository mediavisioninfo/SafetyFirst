<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Claim;
use Illuminate\Support\Str;
use File;
use DB;

class ProcessApprovedClaims extends Command
{
    protected $signature = 'claims:process-approved';
    protected $description = 'Process approved claims and send photos to damage detection API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Cron job started: Process Approved');

        // Fetch all approved claims
        // $approvedClaims = Claim::where('status', 'documents_submitted')->get();
        $approvedClaims = Claim::where('status', 'documents_submitted')->where(function ($query) {
                $query->whereNull('processed_image_files')
                    ->orWhere('processed_image_files', '[]');
            })->get();

        Log::info("Approved Claims: " . json_encode($approvedClaims));

        foreach ($approvedClaims as $claim) {
            $photoFilesJson = $claim->photo_files;
            $photoFiles = json_decode($photoFilesJson, true);

            if (is_array($photoFiles) && !empty($photoFiles)) {
                $damageResults = [];
                $processedImageFiles = [];

                foreach ($photoFiles as $file) {
                    // Extract filename if $file is an array
                    if (is_array($file) && isset($file['filename'])) {
                        $fileName = $file['filename'];
                    } else {
                        $fileName = $file;
                    }

                    $photoFilePath = storage_path('app/public/upload/document/claim-b056eb1587586b71e2da9acfe4fbd19e/photos/vehicle_photos/' . $fileName);

                    if (file_exists($photoFilePath)) {
                        try {
                            $response = Http::attach('image', file_get_contents($photoFilePath), $fileName)
                                ->post('https://combiapi-vace.onrender.com/analyze');

                            if ($response->successful()) {
                                $result = $response->json();
                                Log::info("Damage detection result for file {$fileName}: " . json_encode($result));

                                // Extract damage parts with pricing
                                $damageResults = array_merge(
                                    $damageResults,
                                    $this->processDamageParts($result['damaged_parts'] ?? [])
                                );

                                // Save processed image
                                if (!empty($result['processed_image_base64'])) {
                                    $processedFileName = $this->saveProcessedImage($result['processed_image_base64'], $fileName);
                                    $processedImageFiles[] = $processedFileName;
                                }

                            } else {
                                Log::error("Failed to process {$fileName}: " . $response->body());
                            }
                        } catch (\Exception $e) {
                            Log::error("Error processing {$fileName}: " . $e->getMessage());
                        }
                    } else {
                        Log::error("Photo file not found: {$photoFilePath}");
                    }
                }

                // Save damage results in the claim
                if (!empty($damageResults)) {
                    $claim->damage_result = json_encode($damageResults);
                }

                // Save processed image filenames in the claim
                if (!empty($processedImageFiles)) {
                    $existingProcessedImages = json_decode($claim->processed_image_files, true) ?? [];
                    $updatedProcessedImages = array_merge($existingProcessedImages, $processedImageFiles);
                    $claim->processed_image_files = json_encode($updatedProcessedImages);
                }

                // Save the updated claim
                $claim->save();
                Log::info("Damage results and processed images saved for claim ID {$claim->id}");
            }
        }

        return 0;
    }

    protected function processDamageParts($damagedParts)
    {
        $results = [];
        foreach ($damagedParts as $part) {
            $priceInfo = $this->getDamagePricing($part['class']);
            $results[] = [
                'class' => $part['class'],
                'score' => round($part['score'], 2), // Round score to 2 decimal places
                'severity' => $part['severity'],
                'price' => $priceInfo['price'] ?? 'Not available'
            ];
        }
        return $results;
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

        $priceInfo = [];
        if (isset($mappedParts[$detectedPart])) {
            $mappedPart = $mappedParts[$detectedPart];

            if ($mappedPart) {
                $price = DB::table('parts')
                    ->where('part', 'LIKE', "%{$mappedPart}%")
                    ->first(['rate']);

                if ($price) {
                    $priceInfo['price'] = $price->rate;
                }
            }
        }

        return $priceInfo;
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
}
