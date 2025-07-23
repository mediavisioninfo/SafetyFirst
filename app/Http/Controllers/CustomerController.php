<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage customer')) {
            $customers = User::where('parent_id', parentId())->where('type', 'customer')->get();
            return view('customer.index', compact('customers'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create customer')) {
            $customerNumber = $this->customerNumber();
            $gender = Customer::$gender;
            $maritalStatus = Customer::$maritalStatus;
            return view('customer.create', compact('customerNumber', 'gender', 'maritalStatus'));
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
            $totalCustomer = $authUser->totalCustomer();
            $subscription = Subscription::find($authUser->subscription);
            if ($totalCustomer >= $subscription->customer_limit && $subscription->customer_limit != 0) {
                return redirect()->back()->with('error', __('Your customer limit is over, please upgrade your subscription.'));
            }
            $userRole = Role::where('parent_id', parentId())->where('name', 'customer')->first();
            $customer = new User();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone_number = $request->phone_number;
            $customer->password = \Hash::make(1234);
            $customer->type = $userRole->name;
            $customer->profile = 'avatar.png';
            $customer->lang = 'english';
            $customer->parent_id = parentId();
            $customer->save();
            $customer->assignRole($userRole);
            if (!empty($customer)) {
                $customerDetail = new Customer();
                $customerDetail->user_id = $customer->id;
                $customerDetail->customer_id = $this->customerNumber();
                $customerDetail->company = $request->company;
                $customerDetail->dob = $request->dob;
                $customerDetail->age = $request->age;
                $customerDetail->gender = $request->gender;
                $customerDetail->marital_status = $request->marital_status;
                $customerDetail->blood_group = $request->blood_group;
                $customerDetail->height = $request->height;
                $customerDetail->weight = $request->weight;
                $customerDetail->tax_number = $request->tax_number;
                $customerDetail->city = $request->city;
                $customerDetail->state = $request->state;
                $customerDetail->country = $request->country;
                $customerDetail->zip_code = $request->zip_code;
                $customerDetail->address = $request->address;
                $customerDetail->notes = $request->notes;
                $customerDetail->parent_id = parentId();
                $customerDetail->save();
            }
            return redirect()->route('customer.index')->with('success', __('Customer successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function show($ids)
    {
        if (\Auth::user()->can('show customer')) {
            $id = Crypt::decrypt($ids);
            $customer = User::find($id);
            return view('customer.show', compact('customer'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function edit($ids)
    {
        if (\Auth::user()->can('edit customer')) {
            $id = Crypt::decrypt($ids);
            $customer = User::find($id);
            $gender = Customer::$gender;
            $maritalStatus = Customer::$maritalStatus;
            return view('customer.edit', compact('customer', 'gender', 'maritalStatus'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('edit customer')) {

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

            $customer = User::find($id);
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone_number = $request->phone_number;
            $customer->save();
            if (!empty($customer)) {
                $customerDetail = Customer::where('user_id', $customer->id)->first();
                $customerDetail->company = $request->company;
                $customerDetail->dob = $request->dob;
                $customerDetail->age = $request->age;
                $customerDetail->gender = $request->gender;
                $customerDetail->marital_status = $request->marital_status;
                $customerDetail->blood_group = $request->blood_group;
                $customerDetail->height = $request->height;
                $customerDetail->weight = $request->weight;
                $customerDetail->tax_number = $request->tax_number;
                $customerDetail->city = $request->city;
                $customerDetail->state = $request->state;
                $customerDetail->country = $request->country;
                $customerDetail->zip_code = $request->zip_code;
                $customerDetail->address = $request->address;
                $customerDetail->notes = $request->notes;
                $customerDetail->save();
            }
            return redirect()->route('customer.index')->with('success', __('Customer successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete customer')) {
            $customer = User::find($id);
            Customer::where('user_id', $customer->id)->delete();
            $customer->delete();

            return redirect()->route('customer.index')->with('success', __('Customer successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customerNumber()
    {
        $latestCustomer = Customer::where('parent_id', parentId())->latest()->first();
        if ($latestCustomer == null) {
            return 1;
        } else {
            return $latestCustomer->customer_id + 1;
        }
    }
}
