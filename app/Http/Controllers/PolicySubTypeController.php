<?php

namespace App\Http\Controllers;

use App\Models\PolicySubType;
use App\Models\PolicyType;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;

class PolicySubTypeController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage policy sub type')) {
            $policySubTypes = PolicySubType::where('parent_id', parentId())->get();
            return view('policy_sub_type.index', compact('policySubTypes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $types = PolicyType::where('parent_id',parentId())->get()->pluck('title', 'id');
        $types->prepend(__('Select Policy Type'), '');
        return view('policy_sub_type.create',compact('types'));
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create policy sub type') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                    'type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policySubType = new PolicySubType();
            $policySubType->title = $request->title;
            $policySubType->type = $request->type;
            $policySubType->parent_id = parentId();
            $policySubType->save();

            return redirect()->back()->with('success', __('Policy sub type successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(PolicySubType $policySubType)
    {
        //
    }


    public function edit(PolicySubType $policySubType)
    {
        $types = PolicyType::where('parent_id',parentId())->get()->pluck('title', 'id');
        $types->prepend(__('Select Policy Type'), '');
        return view('policy_sub_type.edit',compact('policySubType','types'));
    }


    public function update(Request $request, PolicySubType $policySubType)
    {
        if (\Auth::user()->can('create policy sub type') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                    'type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $policySubType->title = $request->title;
            $policySubType->type = $request->type;
            $policySubType->parent_id = parentId();
            $policySubType->save();

            return redirect()->back()->with('success', __('Policy sub type successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(PolicySubType $policySubType)
    {
        if (\Auth::user()->can('delete policy sub type') ) {
            $policySubType->delete();
            return redirect()->back()->with('success', 'Policy sub type successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getSubType($type_id)
    {
        $subtypes = PolicySubType::where('type', $type_id)->get()->pluck('title', 'id');
        return response()->json($subtypes);
    }
}
