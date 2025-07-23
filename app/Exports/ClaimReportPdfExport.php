<?php

namespace App\Exports;

use App\Models\Claim;
use App\Models\ProfessionalFee;
use App\Models\VehicleRegistration;
use App\Models\DlDetail;
use App\Models\InsuranceDetail;
use Barryvdh\DomPDF\Facade\Pdf;

class ClaimReportPdfExport
{
    protected $claimId;

    public function __construct($claimId)
    {
        $this->claimId = $claimId;
    }

    public function download()
    {
        // Common
        $claim = Claim::find($this->claimId);
        $insuranceDetail = InsuranceDetail::where('claim_id', $this->claimId)->first();

        // Sheet 1: Fee Bill
        $feesBillData = ProfessionalFee::where('claim_id', $this->claimId)->first();
        $forExcel = false; // set to false if rendering for PDF
        $feeBillHtml = view('exports.fee_bill', compact('feesBillData', 'claim','forExcel','insuranceDetail'))->render();

        // Sheet 2: Particulars
        $vehicleRegistrationData = VehicleRegistration::where('claim_id', $this->claimId)->first();
        $drivingLicenseData = DlDetail::where('claim_id', $this->claimId)->first();
        $forExcel = false; // set to false if rendering for PDF
        $particularsHtml = view('exports.particulars', compact('vehicleRegistrationData', 'drivingLicenseData', 'claim','forExcel','insuranceDetail'))->render();

        // Sheet 3: Assessment
        $damageResultsAll = json_decode($claim->all_damage_result, true);
        $damageTableResult = $damageResultsAll['damageTableData'] ?? [];
        $labourTableResult = $damageResultsAll['labourTableData'] ?? [];
        $summaryTableResult = $damageResultsAll['summaryTableData'] ?? [];
        $forExcel = false; // set to false if rendering for PDF
        $assessmentHtml = view('exports.assessment', compact(
            'claim',
            'damageResultsAll',
            'damageTableResult',
            'labourTableResult',
            'summaryTableResult',
            'forExcel'
        ))->render();

        // Combine views with page breaks
        $fullHtml = $feeBillHtml .
                    $particularsHtml .
                    $assessmentHtml;

        // Generate PDF
        $pdf = Pdf::loadHTML($fullHtml)->setPaper('A4');

        return $pdf->download('claim-report.pdf');
    }
}

