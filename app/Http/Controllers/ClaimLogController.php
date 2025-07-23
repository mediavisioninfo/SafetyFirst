<?php

namespace App\Http\Controllers;

use App\Models\ClaimLog;
use Illuminate\Http\Request;

class ClaimLogController extends Controller
{
    public function index()
    {
        if (!\Auth::user()->can('manage claim')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $logs = ClaimLog::with(['claim', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('claim.logs', compact('logs'));
    }
    public function showChanges($id)
    {
        $log = ClaimLog::findOrFail($id); 

        return view('claim.show_change', compact('log'));
    }
    public function show($id)
    {
        if (!\Auth::user()->can('manage claim')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $logs = ClaimLog::with(['claim', 'user'])
                        ->where('claim_id', $id) 
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
        return view('claim.log', compact('logs'));
    }
}