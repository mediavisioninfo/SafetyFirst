<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_id',
        'customer',
        'policy',
        'agent',
        'agent_commission',
        'status',
        'start_date',
        'due_date',
        'notes',
        'parent_id',
        'policy_term',
        'premium',
        'parent_id',
    ];

    public static $status = [
        'new' => 'New',
        'to_review' => 'To Review',
        'confirm' => 'Confirm',
        'running' => 'Running',
        'expired' => 'Expired',
    ];

    public static $docStatus = [
        'verified' => 'Verified',
        'not_verified' => 'Not Verified',
        'rejected' => 'Rejected',
    ];

    public function policies()
    {
        return $this->hasOne('App\Models\Policy', 'id', 'policy');
    }

    public function customers()
    {
        return $this->hasOne('App\Models\User', 'id', 'customer');
    }

    public function agents()
    {
        return $this->hasOne('App\Models\User', 'id', 'agent');
    }

    public function insureds()
    {
        return $this->hasMany('App\Models\InsuredDetail', 'insurance', 'id');
    }
    public function nominees()
    {
        return $this->hasMany('App\Models\NomineeDetail', 'insurance', 'id');
    }
    public function documents()
    {
        return $this->hasMany('App\Models\InsuranceDocument', 'insurance', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\InsurancePayment', 'insurance', 'id');
    }
}
