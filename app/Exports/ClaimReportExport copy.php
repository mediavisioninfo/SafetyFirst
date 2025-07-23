<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Claim;
use App\Models\VehicleRegistration;
use App\Models\DlDetail;
use App\Models\ProfessionalFee;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate; // ✅ IMPORT THIS
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // ✅ Auto adjust columns    

class ClaimReportExport implements FromView, WithDrawings, ShouldAutoSize
{
    protected $claimId;

    public function __construct($claimId) 
    {
        $this->claimId = $claimId;
    }

    public function view(): View
    {
        $claim = Claim::find($this->claimId);
        
        //fetch data fees bill
        $feesBillData = ProfessionalFee::where('claim_id', $claim->id)->first();

        //fetch data Vehicle Details based on claim id
        $vehicleRegistrationData = VehicleRegistration::where('claim_id', $claim->id)->first();

        //fetch data Driving License Details based on claim Id
        $drivingLicenseData = DlDetail::where('claim_id', $claim->id)->first();

        // dd($drivingLicenseData);
        $damageResultsAll = json_decode($claim->all_damage_result, true);
        $damageTableResult = $damageResultsAll['damageTableData'] ?? [];
        $labourTableResult = $damageResultsAll['labourTableData'] ?? [];
        $summaryTableResult = $damageResultsAll['summaryTableData'] ?? [];

        return view('exports.claim_report', compact('claim', 'damageResultsAll', 'damageTableResult', 'labourTableResult','summaryTableResult','feesBillData','vehicleRegistrationData' , 'drivingLicenseData'));
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Safety First');
        $drawing->setDescription('Safety First Logo');
        $drawing->setPath(public_path('storage/logo/signature.png')); // ✅ Ensure this path is correct
        $drawing->setHeight(80); // ✅ Adjust the size as needed
        $drawing->setCoordinates('B110'); // ✅ Change this to set position in Excel
        return [$drawing];
    }

}
