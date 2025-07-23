<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'customer_id',
        'company',
        'dob',
        'age',
        'gender',
        'marital_status',
        'blood_group',
        'height',
        'weight',
        'tax_number',
        'city',
        'state',
        'country',
        'zip_code',
        'address',
        'notes',
        'parent_id',
    ];

    public static $gender=[
        'Male'=>'Male',
        'Female'=>'Female',
    ];

    public static $maritalStatus=[
        'Unmarried'=>'Unmarried',
        'Married'=>'Married',
    ];

}
