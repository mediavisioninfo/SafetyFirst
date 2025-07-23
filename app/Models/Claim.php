<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;
    protected $fillable=[
        'claim_id',
        'customer',
        'insurance',
        'date',
        'status',
        'reason',
        'notes',
        'parent_id',
        'loss_date',        
        'location',         
        'claim_amount',     
        'policy_number',
        'mobile',           
        'email',  
        'workshop_email',           
        'ensurance_email',            
        'final_bill_files',            
        'payment_receipt_files',         
    ];

    public static $status = [
        'claim_intimated' => 'Claim Intimated',
        'link_shared' => 'Link Shared',
        'documents_pending' => 'Documents Pending',
        'documents_submitted' => 'Documents Submitted',
        'documents_mismatched' => 'Documents Mismatched',
        'under_review' => 'Under Review',
        'rejected' => 'Rejected',
        'approved' => 'Approved',
        'pre_approval_given' => 'Pre-approval Given',
        'final_approval_given' => 'Final Approval Given',
        'under_repair' => 'Under Repair',
        'final_bill_submitted' => 'Final Bill Submitted',
        'claim_settled' => 'Claim Settled (approved / rejected)',
        'final_report_submitted' => 'Final Report Submitted',
    ];    

    public function customers()
    {
        return $this->hasOne('App\Models\User', 'id', 'customer');
    }
    public function insurances()
    {
        return $this->hasOne('App\Models\Insurance', 'id', 'insurance');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\ClaimDocument', 'claim', 'id');
    }
    public function vehicleRegistrations()
    {
        return $this->hasMany('App\Models\VehicleRegistration');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }
}
