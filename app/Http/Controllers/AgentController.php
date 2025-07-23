<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;

class AgentController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage agent')) {
            $agents = User::where('parent_id', parentId())->where('type', 'agent')->get();
            return view('agent.index', compact('agents'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create agent')) {
            $agentNumber = $this->agentNumber();
            return view('agent.create', compact('agentNumber'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create customer')) {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'phone_number' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $ids = parentId();
            $authUser = \App\Models\User::find($ids);
            $totalAgent = $authUser->totalAgent();
            $subscription = Subscription::find($authUser->subscription);
            if ($totalAgent >= $subscription->agent_limit && $subscription->agent_limit != 0) {
                return redirect()->back()->with('error', __('Your agent limit is over, please upgrade your subscription.'));
            }
            $userRole = Role::where('parent_id', parentId())->where('name', 'agent')->first();
            $agent = new User();
            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->phone_number = $request->phone_number;
            $agent->password = \Hash::make(1234);
            $agent->type = $userRole->name;
            $agent->profile = 'avatar.png';
            $agent->lang = 'english';
            $agent->parent_id = parentId();
            $agent->save();
            $agent->assignRole($userRole);
            if (!empty($agent)) {
                $agentDetail = new Agent();
                $agentDetail->user_id = $agent->id;
                $agentDetail->agent_id = $this->agentNumber();
                $agentDetail->company = $request->company;
                $agentDetail->tax_number = $request->tax_number;
                $agentDetail->city = $request->city;
                $agentDetail->state = $request->state;
                $agentDetail->country = $request->country;
                $agentDetail->zip_code = $request->zip_code;
                $agentDetail->address = $request->address;
                $agentDetail->notes = $request->notes;
                $agentDetail->parent_id = parentId();
                $agentDetail->save();
            }
            return redirect()->route('agent.index')->with('success', __('Agent successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function show($ids)
    {
        if (\Auth::user()->can('show agent')) {
            $id = Crypt::decrypt($ids);
            $agent = User::find($id);
            return view('agent.show', compact('agent'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function edit($ids)
    {
        if (\Auth::user()->can('edit agent')) {
            $id = Crypt::decrypt($ids);
            $agent = User::find($id);
            $gender = Customer::$gender;
            $maritalStatus = Customer::$maritalStatus;
            return view('agent.edit', compact('agent', 'gender', 'maritalStatus'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('create customer')) {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'phone_number' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $agent = User::find($id);
            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->phone_number = $request->phone_number;
            $agent->save();
            if (!empty($agent)) {
                $agentDetail = Agent::where('user_id', $agent->id)->first();
                $agentDetail->company = $request->company;
                $agentDetail->tax_number = $request->tax_number;
                $agentDetail->city = $request->city;
                $agentDetail->state = $request->state;
                $agentDetail->country = $request->country;
                $agentDetail->zip_code = $request->zip_code;
                $agentDetail->address = $request->address;
                $agentDetail->notes = $request->notes;
                $agentDetail->save();
            }
            return redirect()->route('agent.index')->with('success', __('Agent successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete agent')) {
            $agent = User::find($id);
            Customer::where('user_id', $agent->id)->delete();
            $agent->delete();
            return redirect()->route('agent.index')->with('success', __('Agent successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function agentNumber()
    {
        $latestAgent = Agent::where('parent_id', parentId())->latest()->first();
        if ($latestAgent == null) {
            return 1;
        } else {
            return $latestAgent->agent_id + 1;
        }
    }
}
