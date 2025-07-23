<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id','professional_fee', 'reinspection_fee', 'date_of_visits', 'halting_charges',
        'conveyance_final', 'distance_final', 'rate_per_km_final',
        'conveyance_reinspection', 'distance_reinspection', 'rate_per_km_reinspection',
        'photos_count', 'photo_rate', 'toll_tax', 'total_amount', 'cgst', 'sgst',
        'igst', 'net_total', 'bank_name', 'branch_name', 'branch_address',
        'account_number', 'ifsc_code', 'micr_code', 'id_no', 'gstin'
    ];
}
