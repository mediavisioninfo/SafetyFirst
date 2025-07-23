<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Auto adjust columns
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use App\Models\ProfessionalFee;
use App\Models\InsuranceDetail;
use App\Models\Claim;

class FeeBillSheet implements FromView,WithTitle, ShouldAutoSize, WithDrawings
{
    protected $claimId;

    public function __construct($claimId) 
    {
        $this->claimId = $claimId;
    }

    public function view(): View
    {
        $claim = Claim::find($this->claimId);
        $feesBillData = ProfessionalFee::where('claim_id', $this->claimId)->first();
        $insuranceDetail = InsuranceDetail::where('claim_id', $this->claimId)->first();
        $forExcel = true;
        return view('exports.fee_bill', compact('feesBillData','claim','forExcel','insuranceDetail'));
    }

    public function title(): string
    {
        return 'FEE BILL';
    }

    // public function drawings()
    // {
    //     $drawing = new Drawing();
    //     $drawing->setName('Safety First');
    //     $drawing->setDescription('Safety First Logo');
    //     $drawing->setPath(public_path('storage/logo/logo.png')); // ✅ Ensure this path is correct
    //     $drawing->setHeight(80); // ✅ Adjust the size as needed
    //     $drawing->setCoordinates('B38'); // ✅ Change this to set position in Excel
    //     return [$drawing];
    // }

    public function drawings()
    {
        $logo = new Drawing();
        $logo->setName('Logo');
        $logo->setDescription('Company Logo');
        $logo->setPath(public_path('storage/logo/safety1logo.png')); // Top logo
        $logo->setHeight(120);
        $logo->setCoordinates('A1'); // Top-left corner
        // Offset to center it (values in pixels — adjust as needed)
        $logo->setOffsetX(40); // Horizontal centering
        $logo->setOffsetY(10); // Vertical centering

        $signature = new Drawing();
        $signature->setName('Signature');
        $signature->setDescription('Official Signature');
        $signature->setPath(public_path('storage/logo/officialSignature.png')); // Bottom signature
        $signature->setHeight(100);
        $signature->setCoordinates('B38'); // Adjust based on layout
        // Offset to move image to top-right inside the cell
        $signature->setOffsetX(80); // Pushes image right (adjust based on column width)
        $signature->setOffsetY(0);  // Stays at the top


        return [$logo, $signature];
    }
}
