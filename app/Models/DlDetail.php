<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DlDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'dl_details';  

    protected $fillable = [
        'claim_id',        // Foreign key referencing the claim
        'license_number',  // License number (string)
        'name',            // Name of the license holder (string)
        'dob',             // Date of birth (date or string, depending on format)
        'father_name',     // Father/Husband's name (string)
        'address',         // Address of the license holder (string)
        'issue_date',      // Issue date of the license (date or string)
        'validity_date',   // Validity date of the license (date or string)
        'vehicle_class',   // Vehicle class (string)
        'state_code',      // State code (e.g., MP, MH, RJ, etc.) (string)
        'license_type',    // Type of license (e.g., Learner, Permanent) (string)
    ];
    
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
