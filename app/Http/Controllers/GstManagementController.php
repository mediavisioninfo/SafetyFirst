<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gst;
class GstManagementController extends Controller
{
    public function index()
    {
        if (!\Auth::user()->can('manage contact')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $loginUser = \Auth::user();
        $gst = Gst::first();

        return view('gst.index', compact('loginUser','gst'));
    }
    public function update(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'cgst' => 'required|numeric|min:0',
            'sgst' => 'required|numeric|min:0',
            'igst' => 'required|numeric|min:0',
        ]);

        // Fetch the existing GST record (assuming one record exists)
        $gst = Gst::first();  // Fetch the first record

        // Check if GST record exists
        if (!$gst) {
            // If no GST record found, create a new one
            $gst = new Gst();
        }

        // Update the values or create a new record
        $gst->cgst = $validated['cgst'];
        $gst->sgst = $validated['sgst'];
        $gst->igst = $validated['igst'];

        // Save the record
        $gst->save();

        // Redirect back with a success message
        return redirect()->route('gst.manage')
            ->with('success', __('GST values updated successfully.'));
    }
}
