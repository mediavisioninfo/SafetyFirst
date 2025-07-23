<?php

namespace App\Services;

class InsuranceDetailExtractor
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
            'policy_number' => [
                'pattern' => '/Policy No\.\\n?\s*(\S+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'previous_policy_number' => [
                'pattern' => '/Previous Policy No\s*:\s*(\S+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'insured_name' => [
                'pattern' => "/Name of the Insured\s*:?\s*([^:\n]+?)(?=\s*Period|Address|\n|$)/i",
                'default' => 'N/A',
                'clean' => true
            ],
            'insured_address' => [
                'pattern' => "/Address of the Insured\s*:?\s*([^:\n].*?)(?=Business|$)/i",
                'default' => 'N/A',
                'clean' => true
            ],
            'insured_declared_value' => [
                'pattern' => "/Insured's Declared Value\s*(?:Rs\.?)?\s*(\d[\d,]+)/i",
                'default' => '0',
                'clean' => true
            ],
            'issuing_office_address_code' => [
                'pattern' => "/Issuing Office Address Code\s*:?\s*([\s\S]*?)(?=nDIVISIONAL|$)/i",
                'default' => 'N/A',
                'clean' => true
            ],
            'issuing_office_address' => [
                'pattern' => "/Issuing Office Address Code\s*:?\s*.*?(DIVISIONAL[\s\S]*?)(?=Tele|$)/i",
                'default' => 'N/A',
                'clean' => true
            ],
            'occupation' => [
                'pattern' => "/Business\/Occupation\s*([\s\S]*?)(?=\s*Mobile\b)/i",
                'default' => 'N/A',
                'clean' => true
            ],
            'mobile' => [
                'pattern' => "/Mobile\s*No\.\s*-\s*(\d{10})/i",
                'default' => 'N/A',
                'clean' => true
            ],
            'vehicle' => [
                'pattern' => "/(?:No\.\s*)?([A-Z]{2}\s*-\s*\d{2}\s*-\s*[A-Z]{2}\s*-\s*\d{4})/i",
                'default' => 'N/A',
                'clean' => true
            ],
            'engine_no' => [
                'pattern' => '/No\s+([A-Z0-9]{12})\s+(MA|MB|MC|MD|ME|MZ)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'chassis_no' => [
                'pattern' => '/\n*M[A-Z0-9]{16}\n*/',
                'default' => 'N/A',
                'clean' => true
            ],
            'make' => [
                'pattern' => '/Make\s*:\s*([A-Za-z0-9-]+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'model' => [
                'pattern' => '/Model\s*:\s*([A-Za-z0-9-]+)/i',
                'default' => 'N/A',
                'clean' => true
            ],
            'year_of_manufacture' => [
                'pattern' => '/Year of Manufacture\s*:?\s*(\d{4})/i',
                'default' => 'N/A',
                'clean' => false
            ],
            'cubic_capacity' => [
                'pattern' => '/CC\/KW\s*:?\s*(\d+)/i',
                'default' => '0',
                'clean' => false
            ],
            'seating_capacity' => [
                'pattern' => '/Seating Capacity\s*:?\s*(\d+)/i',
                'default' => '0',
                'clean' => false
            ],
            'insurance_start_date' => [
                'pattern' => '/Insurance Start Date & Time\s*:?\s*(\d{2}\/\d{2}\/\d{4}\s+\d{2}:\d{2})/i',
                'default' => '',
                'clean' => true
            ],
            'insurance_expiry_date' => [
                'pattern' => '/Insurance expiry Date & Time\s*:?\s*(\d{2}\/\d{2}\/\d{4}\s+midnight)/i',
                'default' => '',
                'clean' => true
            ],
            'no_claim_bonus_percentage' => [
                'pattern' => '/No Claim Bonus\s+(\d+)%/i',
                'default' => '0',
                'clean' => true
            ],
            'nil_depreciation' => [
                'pattern' => '/(Nil Depreciation Without Excess)/i',
                'default' => 'No',
                'clean' => true
            ],
            'additional_towing_charges' => [
                'pattern' => '/Additional Towing Charges\s*\(SI:\s*(\d+)\/-\)/i',
                'default' => '0',
                'clean' => true
            ]
        ];
    }

    public function extract(): array
    {
        $details = [];

        // Extract basic details using patterns
        foreach ($this->patterns as $key => $config) {
            if ($key === 'nil_depreciation') {
                $details[$key] = preg_match($config['pattern'], $this->text) ? 'Yes' : 'No';
            } else {
                $details[$key] = $this->extractValue($config['pattern'], $config['default'], $config['clean']);
            }
        }

        // Add policy type
        $details['policy_type'] = $this->determinePolicyType();

        // Extract insurance period
        //$details['insurance_period'] = $this->extractInsurancePeriod();

        // Extract zero dep
        $details['zero_dep'] = $this->hasZeroDepreciation() ? 'Yes' : 'No';
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

    private function cleanText(string $text): string
    {
        $text = preg_replace('/^n|nStand$/', '', $text);

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

    private function determinePolicyType(): string
    {
        return (stripos($this->text, 'PRIVATE CAR-PACKAGE') !== false)
            ? 'Package Policy'
            : 'Standalone Policy';
    }

    private function extractInsurancePeriod(): array
    {
        $startPattern = '/Insurance Start Date & Time\s*:?\s*(\d{2}\/\d{2}\/\d{4}\s+\d{2}:\d{2})/i';
        $endPattern = '/Insurance expiry Date & Time\s*:?\s*(\d{2}\/\d{2}\/\d{4}\s+midnight)/i';

        $startDate = $this->extractValue($startPattern, '', false);
        $endDate = $this->extractValue($endPattern, '', false);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }

    private function hasZeroDepreciation(): bool
    {
        return stripos($this->text, 'Nil Depreciation Without Excess') !== false;
    }

    private function validateDetails(array $details): array
    {
        // Validate number formats
        $numericFields = ['cubic_capacity', 'seating_capacity', 'no_claim_bonus_percentage', 'additional_towing_charges'];
        foreach ($numericFields as $field) {
            if (isset($details[$field])) {
                $details[$field] = is_numeric($details[$field]) ? $details[$field] : '0';
            }
        }

        // Format IDV with commas
        if (!empty($details['insured_declared_value'])) {
            $details['insured_declared_value'] = number_format((int)str_replace(',', '', $details['insured_declared_value']));
        }

        // Ensure required fields are not empty
        $requiredFields = ['policy_number', 'insured_name', 'vehicle'];
        foreach ($requiredFields as $field) {
            if (empty($details[$field])) {
                $details[$field] = 'N/A';
            }
        }

        return $details;
    }
}