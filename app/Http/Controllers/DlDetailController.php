<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DlDetail;
use Illuminate\Support\Facades\Crypt;


class DlDetailController extends Controller
{
    public function updateDlDetails(Request $request, $claimId)
    {
        // Create a custom validator
        $validator = \Validator::make(
            $request->all(),
            [
                'license_number' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'dob' => 'required|date',
                'father_name' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'issue_date' => 'required|date',
                'validity_date' => 'required|date',
                'vehicle_class' => 'nullable|string|max:255',
                'state_code' => 'nullable|string|max:255',
                'license_type' => 'nullable|string|max:255',
                'issuing_office_address' => 'nullable|string',
            ]
        );

        // Check if validation fails
        if ($validator->fails()) {
            // Get the first error message
            $messages = $validator->getMessageBag();
            
            // Redirect back with the error message and previous input
            return redirect()->back()->with('error', $messages->first())->withInput();
        }

        // Find the DL details for the given claim
        $dlDetail = DlDetail::where('claim_id', $claimId)->first();

        if (!$dlDetail) {
            return redirect()->back()->with('error', __('Driving License details not found.'));
        }

        // Update the DL details
        try {
            // Use the validated input to update the record
            $dlDetail->update($validator->validated());
        } catch (\Exception $e) {
            // If there is an error during the update, show a general error message
            return redirect()->back()->with('error', __('There was an error updating the Driving License details.'));
        }

        // Redirect back to the claim page with success message
        return redirect()->route('claim.show', Crypt::encrypt($claimId))
            ->with('success', __('Driving License details updated successfully.'));
    }

}
