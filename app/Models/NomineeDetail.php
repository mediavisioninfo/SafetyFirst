<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomineeDetail extends Model
{
    use HasFactory;
    protected $fillable=[
        'insurance',
        'name',
        'relation',
        'dob',
        'percentage',
    ];
}
