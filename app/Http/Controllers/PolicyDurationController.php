<?php

namespace App\Http\Controllers;

use App\Models\PolicyDuration;
use Illuminate\Http\Request;

class PolicyDurationController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage policy duration')) {
            $policyDurations = PolicyDuration::where('parent_id', parentId())->get();
            return view('policy_duration.index', compact('policyDurations'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('policy_duration.create');
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create policy duration') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'duration_terms' => 'required',
                    'duration_month' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policyDuration = new PolicyDuration();
            $policyDuration->duration_terms = $request->duration_terms;
            $policyDuration->duration_month = $request->duration_month;
            $policyDuration->parent_id = parentId();
            $policyDuration->save();

            return redirect()->back()->with('success', __('Policy duration successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(PolicyDuration $policyDuration)
    {
        //
    }


    public function edit(PolicyDuration $policyDuration)
    {
        return view('policy_duration.edit',compact('policyDuration'));
    }


    public function update(Request $request, PolicyDuration $policyDuration)
    {
        if (\Auth::user()->can('edit policy duration') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'duration_terms' => 'required',
                    'duration_month' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policyDuration->duration_terms = $request->duration_terms;
            $policyDuration->duration_month = $request->duration_month;
            $policyDuration->save();

            return redirect()->back()->with('success', __('Policy duration successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(PolicyDuration $policyDuration)
    {
        if (\Auth::user()->can('delete policy duration') ) {
            $policyDuration->delete();
            return redirect()->back()->with('success', 'Policy duration successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
