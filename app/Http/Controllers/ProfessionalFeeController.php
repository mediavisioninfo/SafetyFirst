<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfessionalFee;
use Illuminate\Support\Facades\Crypt;

class ProfessionalFeeController extends Controller
{

    public function createOrUpdate(Request $request, $claimId, $id = null)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'professional_fee' => 'required|numeric',
            'reinspection_fee' => 'nullable|numeric',
            'date_of_visits' => 'nullable|date',
            'halting_charges' => 'nullable|numeric',
            'conveyance_final' => 'nullable|numeric',
            'distance_final' => 'nullable|integer', // Fixed: integer instead of numeric
            'rate_per_km_final' => 'nullable|numeric',
            'conveyance_reinspection' => 'nullable|numeric',
            'distance_reinspection' => 'nullable|integer', // Fixed: integer instead of numeric
            'rate_per_km_reinspection' => 'nullable|numeric',
            'photos_count' => 'nullable|integer', // Fixed: integer instead of numeric
            'photo_rate' => 'nullable|numeric',
            'toll_tax' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'cgst' => 'required|numeric',
            'sgst' => 'required|numeric',
            'igst' => 'nullable|numeric',
            'net_total' => 'required|numeric',
            
            // Bank Details (Fixed naming mismatch)
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'branch_address' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:20', // Fixed: Changed 'bank_account' → 'account_number'
            'ifsc_code' => 'required|string|max:11',
            'micr_code' => 'nullable|string|max:9',
            
            // Additional fields
            'id_no' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:20',
        ]);
        

        // Add the claim ID manually to the validated data
        $validatedData['claim_id'] = $claimId;

        // dd($validatedData);
        // Check if the ID is provided and if the record exists
        if ($id) {
            $professionalFee = ProfessionalFee::find($id);
            
            if ($professionalFee) {
                // Update existing record
                $professionalFee->update($validatedData);
                $message = 'Professional Fee details updated successfully.';
            } else {
                // Create a new record if not found
                $professionalFee = ProfessionalFee::create($validatedData);
                $message = 'Professional Fee details added successfully.';
            }
        } else {
            // Create a new record if no ID is provided
            $professionalFee = ProfessionalFee::create($validatedData);
            $message = 'Professional Fee details added successfully.';
        }

        // Redirect with a success message
        return redirect()->route('claim.show', Crypt::encrypt($claimId))->with('success', $message);
    }



}
