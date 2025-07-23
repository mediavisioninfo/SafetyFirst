<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsurancePayment extends Model
{
    use HasFactory;
    protected $fillable=[
        'insurance',
        'payment_date',
        'amount',
        'parent_id',
    ];

    public function insurances(){
        return $this->hasOne('App\Models\Insurance','id','insurance');
    }
}
