<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gst extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'gsts';

    // Fillable columns
    protected $fillable = [
        'cgst',
        'sgst',
        'igst',
    ];

}
