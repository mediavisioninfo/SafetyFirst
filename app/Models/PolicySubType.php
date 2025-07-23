<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicySubType extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'type',
        'parent_id',
    ];

    public function types()
    {
        return $this->hasOne('App\Models\PolicyType', 'id', 'type');
    }
}
