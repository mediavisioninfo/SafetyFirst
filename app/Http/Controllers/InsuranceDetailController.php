<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InsuranceDetail;
use Illuminate\Support\Facades\Crypt;


class InsuranceDetailController extends Controller
{
    //update
    public function update(Request $request, $claimId)
    {
        // Validate the input data
        $validated = $request->validate([
            'policy_type' => 'required|string|max:255',
            'policy_number' => 'required|string|max:255',
            'insured_name' => 'required|string|max:255',
            'insured_address' => 'nullable|string',
            'insured_declared_value' => 'required|string|max:255',
            'issuing_office_address_code' => 'nullable|string|max:255',
            'issuing_office_address' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'vehicle' => 'required|string|max:255',
            'engine_no' => 'nullable|string|max:255',
            'zero_dep' => 'nullable|string|max:255',
            'no_claim_bonus_percentage' => 'nullable|integer',
            'nil_depreciation' => 'nullable|string|max:255',
            'previous_policy_number' => 'nullable|string|max:255',  // Added
            'chassis_no' => 'nullable|string|max:255',  // Added
            'make' => 'required|string|max:255',  // Added
            'model' => 'required|string|max:255',  // Added
            'year_of_manufacture' => 'nullable|string|max:255',  // Added
            'cubic_capacity' => 'nullable|integer',  // Added
            'seating_capacity' => 'nullable|integer',  // Added
            'insurance_start_date' => 'nullable|date',  // Added
            'insurance_expiry_date' => 'nullable|date',  // Added
            'additional_towing_charges' => 'nullable|integer',  // Added
        ]);

        // Find the insurance details for the given claim
        $insuranceDetail = InsuranceDetail::where('claim_id', $claimId)->first();

        // echo $insuranceDetail;
        if (!$insuranceDetail) {
            return redirect()->back()->with('error', __('Insurance details not found.'));
        }

        // dd($insuranceDetail);
        // Update the insurance details
        $insuranceDetail->update($validated);

        return redirect()->route('claim.show', Crypt::encrypt($claimId))
            ->with('success', __('Insurance details updated successfully.'));
    }

}
