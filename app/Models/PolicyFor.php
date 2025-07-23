<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyFor extends Model
{
    use HasFactory;
    protected $fillable=[
        'policy_type',
        'buying_for',
        'parent_id',
    ];

    public function types()
    {
        return $this->hasOne('App\Models\PolicyType', 'id', 'policy_type');
    }
}
