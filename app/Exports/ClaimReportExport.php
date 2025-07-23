<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClaimReportExport implements WithMultipleSheets
{
    protected $claimId;

    
    public function __construct($claimId)
    {
        $this->claimId = $claimId;
    }

    public function sheets(): array
    {
        return [
            new FeeBillSheet($this->claimId),
            new ParticularsSheet($this->claimId),
            new AssessmentSheet($this->claimId),
        ];
    }
}
