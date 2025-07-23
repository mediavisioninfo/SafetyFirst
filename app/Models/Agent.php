<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'agent_id',
        'company',
        'tax_number',
        'city',
        'state',
        'country',
        'zip_code',
        'address',
        'parent_id',
        'notes',
    ];
}
