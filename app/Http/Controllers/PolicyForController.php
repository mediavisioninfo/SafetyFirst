<?php

namespace App\Http\Controllers;

use App\Models\PolicyFor;
use App\Models\PolicyType;
use Illuminate\Http\Request;

class PolicyForController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage policy for')) {
            $policyFors = PolicyFor::where('parent_id', parentId())->get();
            return view('policy_for.index', compact('policyFors'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $types = PolicyType::where('parent_id',parentId())->get()->pluck('title', 'id');
        $types->prepend(__('Select Policy Type'), '');
        return view('policy_for.create',compact('types'));
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create policy for') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'policy_type' => 'required',
                    'buying_for' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policyFor = new PolicyFor();
            $policyFor->policy_type = $request->policy_type;
            $policyFor->buying_for = $request->buying_for;
            $policyFor->parent_id = parentId();
            $policyFor->save();

            return redirect()->back()->with('success', __('Policy for successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(PolicyFor $policyFor)
    {
        //
    }


    public function edit(PolicyFor $policyFor)
    {
        $types = PolicyType::where('parent_id',parentId())->get()->pluck('title', 'id');
        $types->prepend(__('Select Policy Type'), '');
        return view('policy_for.edit',compact('policyFor','types'));
    }


    public function update(Request $request, PolicyFor $policyFor)
    {
        if (\Auth::user()->can('edit policy for') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'policy_type' => 'required',
                    'buying_for' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policyFor->policy_type = $request->policy_type;
            $policyFor->buying_for = $request->buying_for;
            $policyFor->save();

            return redirect()->back()->with('success', __('Policy for successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(PolicyFor $policyFor)
    {
        if (\Auth::user()->can('delete policy for') ) {
            $policyFor->delete();
            return redirect()->back()->with('success', 'Policy for successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
