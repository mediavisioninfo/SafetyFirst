<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceDocument extends Model
{
    use HasFactory;
    protected $fillable=[
        'insurance',
        'document_type',
        'document',
        'type',
        'status',
    ];

    public function types()
    {
        return $this->hasOne('App\Models\DocumentType', 'id', 'document_type');
    }
}
