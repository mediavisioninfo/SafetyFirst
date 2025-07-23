<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;

class ClaimSummarySheet implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $claimId;

    public function __construct($claimId)
    {
        $this->claimId = $claimId;
    }

    public function collection()
    {
        return Claim::select(DB::raw("DATE(created_at) as claim_date"), DB::raw("COUNT(*) as total_claims"))
            ->groupBy('claim_date')
            ->orderBy('claim_date', 'desc') // Show latest date first
            ->get();
    }

    public function headings(): array
    {
        return [
            'Claim Date',
            'Total Claims Created'
        ];
    }
}

