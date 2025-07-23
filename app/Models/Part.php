<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;
     // Specify the table name if it doesn't follow Laravel's naming conventions
     protected $table = 'parts'; // Adjust if your table name is different

     // Define fillable attributes
     protected $fillable = [
         'class',
         'price',
         'score',
         'severity',
         'material',
     ];
 
}
