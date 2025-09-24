<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    
    // protected $fillable = ['template_id', 'subject', 'body'];
    protected $fillable = [
        'template_id',
        'claim_id',
        'recipients',
        'cc',
        'subject',
        'body',
        'status'];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class);
    }
}
