<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Auto adjust columns
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use App\Models\Claim;

class AssessmentSheet implements FromView, WithTitle, ShouldAutoSize, WithDrawings
{
    protected $claimId;

    public function __construct($claimId) 
    {
        $this->claimId = $claimId;
    }

    public function view(): View
    {
        $claim = Claim::find($this->claimId);
        $damageResultsAll = json_decode($claim->all_damage_result, true);
        $damageTableResult = $damageResultsAll['damageTableData'] ?? [];
        $labourTableResult = $damageResultsAll['labourTableData'] ?? [];
        $summaryTableResult = $damageResultsAll['summaryTableData'] ?? [];
        $forExcel = true;
        return view('exports.assessment', compact('claim','damageResultsAll', 'damageTableResult', 'labourTableResult', 'summaryTableResult','forExcel'));
    }

    public function title(): string
    {
        return 'Assessment';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Safety First');
        $drawing->setDescription('Safety First Logo');
        $drawing->setPath(public_path('storage/logo/signature.png')); // ✅ Ensure this path is correct
        $drawing->setHeight(120); // ✅ Adjust the size as needed
        $drawing->setCoordinates('B40'); // ✅ Change this to set position in Excel
        return [$drawing];
    }
}
