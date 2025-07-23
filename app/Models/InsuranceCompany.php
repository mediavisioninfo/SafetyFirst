<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'created_at',
        'updated_at',
    ];

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
