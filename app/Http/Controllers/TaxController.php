<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage tax')) {
            $taxs = Tax::where('parent_id', parentId())->get();
            return view('tax.index', compact('taxs'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('tax.create');
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create tax')) {
            $validator = \Validator::make(
                $request->all(), [
                    'tax' => 'required',
                    'rate' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $tax = new Tax();
            $tax->tax = $request->tax;
            $tax->rate = $request->rate;
            $tax->parent_id = \Auth::user()->id;
            $tax->save();
            return redirect()->back()->with('success', __('Tax successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Tax $tax)
    {

    }


    public function edit(Tax $tax)
    {
        return view('tax.edit', compact('tax'));
    }


    public function update(Request $request, Tax $tax)
    {

        if (\Auth::user()->can('edit tax')) {
            $validator = \Validator::make(
                $request->all(), [
                    'tax' => 'required',
                    'rate' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $tax->tax = $request->tax;
            $tax->rate = $request->rate;
            $tax->save();
            return redirect()->back()->with('success', __('Tax successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Tax $tax)
    {

        if (\Auth::user()->can('delete tax') ) {
            $tax->delete();
            return redirect()->back()->with('success', 'Tax successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
