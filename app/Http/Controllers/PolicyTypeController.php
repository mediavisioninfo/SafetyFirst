<?php

namespace App\Http\Controllers;

use App\Models\PolicyType;
use Illuminate\Http\Request;

class PolicyTypeController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage policy type')) {
            $policyTypes = PolicyType::where('parent_id', parentId())->get();
            return view('policy_type.index', compact('policyTypes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('policy_type.create');
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create policy type') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policyType = new PolicyType();
            $policyType->title = $request->title;
            $policyType->parent_id = parentId();
            $policyType->save();

            return redirect()->back()->with('success', __('Policy type successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(PolicyType $policyType)
    {
        //
    }


    public function edit(PolicyType $policyType)
    {

        return view('policy_type.edit', compact('policyType'));
    }


    public function update(Request $request, PolicyType $policyType)
    {
        if (\Auth::user()->can('edit policy type') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policyType->title = $request->title;
            $policyType->save();

            return redirect()->back()->with('success', __('Policy type successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(PolicyType $policyType)
    {
        if (\Auth::user()->can('delete policy type') ) {
            $policyType->delete();
            return redirect()->back()->with('success', 'Policy type successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
