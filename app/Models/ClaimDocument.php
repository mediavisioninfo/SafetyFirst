<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDocument extends Model
{
    use HasFactory;
    protected $fillable=[
        'claim',
        'document_type',
        'document',
        'status',
    ];
    public function types()
    {
        return $this->hasOne('App\Models\DocumentType', 'id', 'document_type');
    }
}
