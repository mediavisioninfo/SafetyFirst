<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'liability_risk',
        'coverage_type',
        'policy_type',
        'policy_subtype',
        'sum_assured',
        'total_insured_person',
        'policy_required_document',
        'claim_required_document',
        'pricing',
        'terms_conditions',
        'description',
        'parent_id',
        'premium',
        'tax',
    ];

    public static $coverageType=[
        'individual'=>'Individual',
        'family'=>'Family',
        'group'=>'Group',
    ];

    public static $liabilityRisk=[
        'auto'=>'Auto',
        'homeowners'=>'Home Owners',
        'business'=>'Business',
    ];

    public function types()
    {
        return $this->hasOne('App\Models\PolicyType', 'id', 'policy_type');
    }

    public function subtypes()
    {
        return $this->hasOne('App\Models\PolicySubType', 'id', 'policy_subtype');
    }

    public function documentTypes($id)
    {
        return DocumentType::whereIn('id',explode(',',$id))->get();
    }

    public function policyFor()
    {
        return $this->hasOne('App\Models\PolicyFor', 'id', 'policy_type');
    }
}
