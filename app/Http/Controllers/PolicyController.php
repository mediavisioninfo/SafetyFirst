<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\Policy;
use App\Models\PolicyDuration;
use App\Models\PolicyType;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PolicyController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage policy')) {
            $policies = Policy::where('parent_id', parentId())->get();
            return view('policy.index', compact('policies'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create policy')) {
            $policyType = PolicyType::where('parent_id', parentId())->get()->pluck('title', 'id');
            $policyType->prepend(__('Select Policy Type'), '');

            $documentType = DocumentType::where('parent_id', parentId())->get()->pluck('title', 'id');
            $coverageType=Policy::$coverageType;
            $liabilityRisk=Policy::$liabilityRisk;
            $durations=PolicyDuration::where('parent_id', parentId())->get();

            $taxes=Tax::where('parent_id',parentId())->get()->pluck('tax','id');
            return view('policy.create', compact('policyType', 'documentType','liabilityRisk','coverageType','durations','taxes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {

        if (\Auth::user()->can('create policy')) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                    'liability_risk' => 'required',
                    'coverage_type' => 'required',
                    'policy_type' => 'required',
                    'policy_subtype' => 'required',
                    'sum_assured' => 'required',
                    'policy_required_document' => 'required',
                    'claim_required_document' => 'required',
                    'duration_terms.*' => 'required',
                    'price.*' => 'required',
                    'terms_conditions' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }
            $policy = new Policy();
            $policy->title = $request->title;
            $policy->liability_risk = $request->liability_risk;
            $policy->coverage_type = $request->coverage_type;
            $policy->policy_type = $request->policy_type;
            $policy->policy_subtype = $request->policy_subtype;
            $policy->sum_assured = $request->sum_assured;
            $policy->total_insured_person = $request->total_insured_person;
            $policy->policy_required_document = implode(',',$request->policy_required_document);
            $policy->claim_required_document = implode(',',$request->claim_required_document);
            $policy->terms_conditions = !empty($request->terms_conditions)?$request->terms_conditions:null;
            $policy->tax = !empty($request->tax)?implode(',',$request->tax):null;
            $policy->description = !empty($request->description)?$request->description:null;
            $policy->parent_id = parentId();
            $pricingArr=[];
            $price=$request->price;
            $duration_month=$request->duration_month;
            foreach ($request->duration_terms as $key=> $pricing){
                $data['duration_terms']=$pricing;
                $data['duration_month']=$duration_month[$key];
                $data['price']=$price[$key];
                $pricingArr[]=$data;
            }
            $policy->pricing=json_encode($pricingArr);
            $policy->save();

            return redirect()->route('policy.index')->with('success', __('Policy successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($ids)
    {
        $id=Crypt::decrypt($ids);
        $policy=Policy::find($id);
        $policy->pricing=json_decode($policy->pricing);
        return view('policy.show', compact('policy'));
    }


    public function edit($ids)
    {
        if (\Auth::user()->can('edit policy')) {
            $id=Crypt::decrypt($ids);
            $policy=Policy::find($id);
            $policy->pricing=json_decode($policy->pricing);
            $policyType = PolicyType::where('parent_id', parentId())->get()->pluck('title', 'id');
            $policyType->prepend(__('Select Policy Type'), '');

            $documentType = DocumentType::where('parent_id', parentId())->get()->pluck('title', 'id');
            $coverageType=Policy::$coverageType;
            $liabilityRisk=Policy::$liabilityRisk;
            $durations=PolicyDuration::where('parent_id', parentId())->get();
            $taxes=Tax::where('parent_id',parentId())->get()->pluck('tax','id');
            return view('policy.edit', compact('policyType', 'documentType','policy','liabilityRisk','coverageType','durations','taxes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Policy $policy)
    {
        if (\Auth::user()->can('edit policy')) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                    'liability_risk' => 'required',
                    'coverage_type' => 'required',
                    'policy_type' => 'required',
                    'policy_subtype' => 'required',
                    'sum_assured' => 'required',
                    'policy_required_document' => 'required',
                    'claim_required_document' => 'required',
                    'duration_terms.*' => 'required',
                    'price.*' => 'required',
                    'terms_conditions' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $policy->title = $request->title;
            $policy->liability_risk = $request->liability_risk;
            $policy->coverage_type = $request->coverage_type;
            $policy->policy_type = $request->policy_type;
            $policy->policy_subtype = $request->policy_subtype;
            $policy->sum_assured = $request->sum_assured;
            $policy->total_insured_person = $request->total_insured_person;
            $policy->policy_required_document = implode(',',$request->policy_required_document);
            $policy->claim_required_document = implode(',',$request->claim_required_document);
            $policy->terms_conditions = !empty($request->terms_conditions)?$request->terms_conditions:null;
            $policy->tax = !empty($request->tax)?implode(',',$request->tax):null;
            $policy->description = !empty($request->description)?$request->description:null;
            $policy->parent_id = parentId();
            $pricingArr=[];
            $price=$request->price;
            $duration_month=$request->duration_month;
            foreach ($request->duration_terms as $key=> $pricing){
                $data['duration_terms']=$pricing;
                $data['price']=$price[$key];
                $data['duration_month']=$duration_month[$key];
                $pricingArr[]=$data;
            }
            $policy->pricing=json_encode($pricingArr);
            $policy->save();

            return redirect()->route('policy.index')->with('success', __('Policy successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Policy $policy)
    {
        if (\Auth::user()->can('delete policy') ) {
            $policy->delete();
            return redirect()->back()->with('success', 'Policy successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
