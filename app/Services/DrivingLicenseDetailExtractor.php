<?php

namespace App\Services;

class DrivingLicenseDetailExtractor
{
    private $text;
    private $patterns;

    public function __construct($ocrText)
    {
        $this->text = $ocrText;
        $this->initializePatterns();
    }

    private function initializePatterns()
    {
        $this->patterns = [
            'license_number' => [
                'pattern' => '/[A-Z]{2}\d{2}[A-Z]{1,2}-\d{4}-\d{7}/',  // License Number (any state code)
                'default' => 'N/A',
                'clean' => true
            ],
            'name' => [
                'pattern' => '/Name:\s*([A-Za-z\s]+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'dob' => [
                'pattern' => '/Date of Birth:\s*(\d{2}-\d{2}-\d{4})/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'father_name' => [
                'pattern' => '/Son\/Daughter\/Wife of:\s*([A-Za-z\s]+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'address' => [
                'pattern' => '/Address:\s*([A-Za-z0-9\s,]+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'issue_date' => [
                'pattern' => '/Issued on\s*:\s*(\d{2}-\d{2}-\d{4})/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'validity_date' => [
                'patterns' => [
                    '/Valid till\s*:\s*(\d{2}-\d{2}-\d{4})/i', // Validity Date pattern 1
                    '/Expiry Date:\s*(\d{2}-\d{2}-\d{4})/i', 
                    '/Valid\s*(Till|throughout)\s*(India)?[:\-]?\s*(\d{2}\/\d{2}\/\d{4})/i',
                    '/(\d{2}\/\d{2}\/\d{4}\(NT\))/i', // Correct pattern for (NT) date
                ],
                'default' => 'N/A',
                'clean' => true
            ],
            'vehicle_class' => [
                'pattern' => '/Vehicle Class\s*:\s*([A-Za-z0-9\s]+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'state_code' => [
                'pattern' => '/\b[A-Z]{2}\d{2}[A-Z]{1,2}-\d{4}-\d{7}/',  // State Code (e.g., MH, RJ, UP)
                'default' => 'N/A',
                'clean' => true
            ],
            'license_type' => [
                'pattern' => '/Licence to drive\s*([A-Za-z\s]+)/i',
                'default' => 'N/A',
                'clean' => true
            ]
        ];
    }

    public function extract(): array
    {
        $details = [];

        // Extract basic details using patterns
        foreach ($this->patterns as $key => $config) {
            if (isset($config['patterns'])) {
                // If multiple patterns exist for this field (e.g., date), try all patterns
                $details[$key] = $this->extractMultiplePatterns($config['patterns'], $config['default'], $config['clean']);
            } else {
                $details[$key] = $this->extractValue($config['pattern'], $config['default'], $config['clean']);
            }
        }

        return $this->validateDetails($details);
    }

    private function extractValue(string $pattern, string $default = '', bool $clean = true): string
    {
        if (preg_match($pattern, $this->text, $matches)) {
            $value = $matches[1] ?? $default;
            return $clean ? $this->cleanText($value) : $value;
        }
        return $default;
    }

    private function extractMultiplePatterns(array $patterns, string $default = '', bool $clean = true): string
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->text, $matches)) {
                $value = $matches[1] ?? $default;
                return $clean ? $this->cleanText($value) : $value;
            }
        }
        return $default;
    }

    private function cleanText(string $text): string
    {
        // Clean unwanted characters and trim spaces
        return trim(preg_replace([
            '/[\r\n\t]+/',    // Remove newlines, tabs
            '/\s+/',          // Replace multiple spaces with single space
            '/[^\w\s\-,\.:\/]/'  // Remove special characters except -, ., ,, :, /
        ], [
            ' ',
            ' ',
            ''
        ], $text));
    }

    private function validateDetails(array $details): array
    {
        // Ensure required fields are not empty
        $requiredFields = ['license_number', 'name', 'dob', 'address', 'issue_date', 'validity_date'];
        foreach ($requiredFields as $field) {
            if (empty($details[$field])) {
                $details[$field] = 'N/A';
            }
        }

        return $details;
    }
}
