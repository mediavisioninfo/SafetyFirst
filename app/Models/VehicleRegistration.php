<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRegistration extends Model
{
    protected $fillable = [
        'claim_id',
        'rc_number',
        'registration_date',
        'owner_name',
        'owner_number',
        'vehicle_category',
        'vehicle_chasi_number',
        'vehicle_engine_number',
        'maker_model',
        'body_type',
        'fuel_type',
        'color',
        'financed',
        'fit_up_to',
        'insurance_upto',
        'rc_status',
        'blacklist_status',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'fit_up_to' => 'date',
        'insurance_upto' => 'date',
        'financed' => 'boolean',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}