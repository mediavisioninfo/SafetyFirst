<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyDuration extends Model
{
    use HasFactory;
    protected $fillable=[
        'duration_terms',
        'duration_month',
        'parent_id',
    ];
}
