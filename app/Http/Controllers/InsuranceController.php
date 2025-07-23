<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DocumentType;
use App\Models\Insurance;
use App\Models\InsuranceDocument;
use App\Models\InsurancePayment;
use App\Models\InsuredDetail;
use App\Models\NomineeDetail;
use App\Models\Policy;
use App\Models\PolicyDuration;
use App\Models\PolicyType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InsuranceController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage insurance')) {
            $insurances = Insurance::where('parent_id', parentId())->get();
            return view('insurance.index', compact('insurances'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create insurance')) {
            $policy = Policy::where('parent_id', parentId())->get()->pluck('title', 'id');
            $policy->prepend(__('Select Policy'), '');

            $customer = User::where('parent_id', parentId())->where('type', 'customer')->get()->pluck('name', 'id');
            $customer->prepend(__('Select Customer'), '');

            $agent = User::where('parent_id', parentId())->where('type', 'agent')->get()->pluck('name', 'id');
            $agent->prepend(__('Select Agent'), '');

            $insuranceNumber = $this->insuranceNumber();
            $status = Insurance::$status;
            return view('insurance.create', compact('policy', 'insuranceNumber', 'customer', 'agent', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {

        if (\Auth::user()->can('create insurance')) {
            $validator = \Validator::make(
                $request->all(), [
                    'policy' => 'required',
                    'status' => 'required',
                    'start_date' => 'required',
                    'policy_terms' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }
            $policyTerms = explode('-', $request->policy_terms);
            $policyTerm = $policyTerms[1];
            $premium = $policyTerms[2];
            $dueDate = date('Y-m-d', strtotime("+" . $policyTerm . " months", strtotime($request->start_date)));

            $insurance = new Insurance();
            $insurance->insurance_id = $this->insuranceNumber();
            $insurance->customer = $request->customer;
            $insurance->policy = $request->policy;
            $insurance->agent = $request->agent;
            $insurance->agent_commission = $request->agent_commission;
            $insurance->status = $request->status;
            $insurance->start_date = $request->start_date;
            $insurance->due_date = $dueDate;
            $insurance->policy_term = $policyTerm;
            $insurance->premium = $premium;
            $insurance->notes = $request->notes;
            $insurance->parent_id = parentId();
            $insurance->save();

            return redirect()->route('insurance.show', Crypt::encrypt($insurance->id))->with('success', __('Insurance successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($ids)
    {
        if (\Auth::user()->can('show insurance')) {
            $id = Crypt::decrypt($ids);
            $insurance = Insurance::find($id);
            return view('insurance.show', compact('insurance'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($ids)
    {
        if (\Auth::user()->can('edit insurance')) {
            $id = Crypt::decrypt($ids);
            $insurance = Insurance::find($id);

            $policy = Policy::where('parent_id', parentId())->get()->pluck('title', 'id');
            $policy->prepend(__('Select Policy'), '');

            $customer = User::where('parent_id', parentId())->where('type', 'customer')->get()->pluck('name', 'id');
            $customer->prepend(__('Select Customer'), '');

            $agent = User::where('parent_id', parentId())->where('type', 'agent')->get()->pluck('name', 'id');
            $agent->prepend(__('Select Agent'), '');

            $status = Insurance::$status;

            return view('insurance.edit', compact('insurance', 'customer', 'agent', 'status', 'policy'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('create insurance')) {
            $validator = \Validator::make(
                $request->all(), [
                    'policy' => 'required',
                    'status' => 'required',
                    'start_date' => 'required',
                    'policy_terms' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }
            $policyTerms = explode('-', $request->policy_terms);
            $policyTerm = $policyTerms[1];
            $premium = $policyTerms[2];
            $dueDate = date('Y-m-d', strtotime("+" . $policyTerm . " months", strtotime($request->start_date)));

            $insurance = Insurance::find($id);
            $insurance->customer = $request->customer;
            $insurance->policy = $request->policy;
            $insurance->agent = $request->agent;
            $insurance->agent_commission = $request->agent_commission;
            $insurance->status = $request->status;
            $insurance->start_date = $request->start_date;
            $insurance->due_date = $dueDate;
            $insurance->policy_term = $policyTerm;
            $insurance->premium = $premium;
            $insurance->notes = $request->notes;
            $insurance->save();

            return redirect()->route('insurance.index')->with('success', __('Insurance successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Insurance $insurance)
    {
        if (\Auth::user()->can('delete insurance')) {
            $id = $insurance->id;
            InsuredDetail::where('insurance', $id)->delete();
            NomineeDetail::where('insurance', $id)->delete();
            InsurancePayment::where('insurance', $id)->delete();
            InsuranceDocument::where('insurance', $id)->delete();
            $insurance->delete();
            return redirect()->back()->with('success', 'Insurance successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function insuranceNumber()
    {
        $latestInsurance = Insurance::where('parent_id', parentId())->latest()->first();
        if ($latestInsurance == null) {
            return 1;
        } else {
            return $latestInsurance->insurance_id + 1;
        }
    }

    public function getUser(Request $request)
    {
        $user = User::find($request->user);
        $user->customer = $user->customer;
        return response()->json($user);
    }

    public function getPolicy(Request $request)
    {
        $policy = Policy::find($request->policy);
        $policy->policy_type = !empty($policy->types) ? $policy->types->title : '';
        $policy->policy_subtype = !empty($policy->subtypes) ? $policy->subtypes->title : '';
        $policy->pricing = !empty($policy->pricing) ? json_decode($policy->pricing) : '';
        return response()->json($policy);
    }

    public function insuredCreate($insuranceId)
    {
        $gender = Customer::$gender;
        return view('insurance.insured_create', compact('insuranceId', 'gender'));
    }

    public function insuredStore(Request $request, $insuranceId)
    {
        if (\Auth::user()->can('create insured detail')) {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'dob' => 'required',
                    'age' => 'required',
                    'gender' => 'required',
                    'blood_group' => 'required',
                    'height' => 'required',
                    'weight' => 'required',
                    'relation' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $insuredDetail = new InsuredDetail();
            $insuredDetail->insurance = $insuranceId;
            $insuredDetail->name = $request->name;
            $insuredDetail->dob = $request->dob;
            $insuredDetail->age = $request->age;
            $insuredDetail->gender = $request->gender;
            $insuredDetail->blood_group = $request->blood_group;
            $insuredDetail->height = $request->height;
            $insuredDetail->weight = $request->weight;
            $insuredDetail->relation = $request->relation;
            $insuredDetail->save();

            return redirect()->back()->with('success', __('Insured successfully added.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function insuredDestroy($insuranceId, $insuredId)
    {
        if (\Auth::user()->can('delete insured detail')) {
            $insured = InsuredDetail::find($insuredId);
            $insured->delete();
            return redirect()->back()->with('success', 'Insured successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function nomineeCreate($insuranceId)
    {
        return view('insurance.nominee_create', compact('insuranceId'));
    }

    public function nomineeStore(Request $request, $insuranceId)
    {
        if (\Auth::user()->can('create nominee')) {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'dob' => 'required',
                    'relation' => 'required',
                    'percentage' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $insuredDetail = new NomineeDetail();
            $insuredDetail->insurance = $insuranceId;
            $insuredDetail->name = $request->name;
            $insuredDetail->dob = $request->dob;
            $insuredDetail->relation = $request->relation;
            $insuredDetail->percentage = $request->percentage;
            $insuredDetail->save();

            return redirect()->back()->with('success', __('Nominee successfully added.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function nomineeDestroy($insuranceId, $nomineeId)
    {
        if (\Auth::user()->can('delete nominee')) {
            $nominee = NomineeDetail::find($nomineeId);
            $nominee->delete();
            return redirect()->back()->with('success', 'Nominee successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function documentCreate($insuranceId)
    {
        $insurance = Insurance::find($insuranceId);
        $docTypes = !empty($insurance->policies) ? explode(',', $insurance->policies->policy_required_document) : [];
        $documentType = DocumentType::whereIn('id', $docTypes)->get()->pluck('title', 'id');
        $documentType->prepend(__('Select Document'), '');

        $status = Insurance::$docStatus;
        return view('insurance.document_create', compact('insuranceId', 'status', 'documentType'));
    }

    public function documentStore(Request $request, $insuranceId)
    {
        if (\Auth::user()->can('create document')) {
            $validator = \Validator::make(
                $request->all(), [
                    'document_type' => 'required',
                    'document' => 'required',
                    'status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $document = new InsuranceDocument();
            $document->insurance = $insuranceId;
            $document->document_type = $request->document_type;
            $document->status = $request->status;

            if (!empty($request->document)) {
                $documentFilenameWithExt = $request->file('document')->getClientOriginalName();
                $documentFilename = pathinfo($documentFilenameWithExt, PATHINFO_FILENAME);
                $documentExtension = $request->file('document')->getClientOriginalExtension();
                $documentFileName = $documentFilename . '_' . time() . '.' . $documentExtension;
                $directory = storage_path('upload/document');
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }
                $request->file('document')->storeAs('upload/document/', $documentFileName);
                $document->document = $documentFileName;
            }

            $document->save();
            return redirect()->back()->with('success', __('Document successfully added.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function documentDestroy($insuranceId, $documentId)
    {
        if (\Auth::user()->can('delete document')) {
            $document = InsuranceDocument::find($documentId);
            $document->delete();
            return redirect()->back()->with('success', 'Document successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function paymentCreate($insuranceId)
    {
        $insurance = Insurance::find($insuranceId);
        $taxRate = !empty($insurance->policies->tax) ? getTaxRate($insurance->policies->tax) : 0;
        $taxAmount = !empty($insurance->premium) ? taxRate($taxRate,$insurance->premium) : 0;
        $amount = !empty($insurance) ? $insurance->premium + $taxAmount : 0;

        return view('insurance.payment_create', compact('insuranceId', 'amount'));
    }

    public function paymentStore(Request $request, $insuranceId)
    {
        if (\Auth::user()->can('create payment')) {
            $validator = \Validator::make(
                $request->all(), [
                    'payment_date' => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }

            $payment = new InsurancePayment();
            $payment->insurance = $insuranceId;
            $payment->payment_date = $request->payment_date;
            $payment->amount = $request->amount;
            $payment->parent_id = parentId();
            $payment->save();
            return redirect()->back()->with('success', __('Payment successfully added.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function paymentDestroy($insuranceId, $paymentId)
    {
        if (\Auth::user()->can('delete payment')) {
            $payment = InsurancePayment::find($paymentId);
            $payment->delete();
            return redirect()->back()->with('success', 'Payment successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function payment()
    {
        if (\Auth::user()->can('manage payment')) {
            $payments = InsurancePayment::where('parent_id', parentId())->get();
            return view('insurance.payment', compact('payments'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
