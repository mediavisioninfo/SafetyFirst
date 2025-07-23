<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuredDetail extends Model
{
    use HasFactory;
    protected $fillable=[
        'insurance',
        'name',
        'dob',
        'age',
        'gender',
        'blood_group',
        'height',
        'weight',
        'relation',
    ];
}
