<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceDetail extends Model
{
    use HasFactory;

    // Define the table name (if it's not the plural form of the model name)
    protected $table = 'insurance_details';

    // Specify the primary key, if it's not the default 'id'
    protected $primaryKey = 'id';

    // Allow mass assignment for the following attributes
    protected $fillable = [
        'claim_id',
        'policy_number',
        'previous_policy_number',
        'insured_name',
        'insured_address',
        'insured_declared_value',
        'issuing_office_address_code',
        'issuing_office_address',
        'occupation',
        'mobile',
        'vehicle',
        'engine_no',
        'chassis_no',
        'make',
        'model',
        'year_of_manufacture',
        'cubic_capacity',
        'seating_capacity',
        'insurance_start_date',
        'insurance_expiry_date',
        'no_claim_bonus_percentage',
        'nil_depreciation',
        'additional_towing_charges',
        'policy_type',
        'zero_dep',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
