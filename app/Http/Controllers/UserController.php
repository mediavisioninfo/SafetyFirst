<?php

namespace App\Http\Controllers;

use App\Models\LoggedHistory;
use App\Models\Subscription;
use App\Models\User;
use App\Models\InsuranceCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /*public function index(Request $request)
    {
        if (\Auth::user()->can('manage user')) {
            if (\Auth::user()->type == 'super admin') {
                $users = User::where('parent_id', parentId())->where('type', 'owner')->get();
                return view('user.index', compact('users'));
            } else {  
                // Get all companies for dropdown
                $companies = \App\Models\InsuranceCompany::all();

                $users = User::where('parent_id', '=', parentId())->whereNotIn('type', ['customer', 'agent'])->get();
                return view('user.index', compact('users','companies','users1'));
            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }*/

    public function index(Request $request)
    {
        if (\Auth::user()->can('manage user')) {
            if (\Auth::user()->type == 'super admin') {
                $users = User::where('parent_id', parentId())
                            ->where('type', 'owner')
                            ->get();

                return view('user.index', compact('users'));
            } else {
                // Get all companies for dropdown
                $companies = InsuranceCompany::all();

                // Default: get all users under current parent
                $users = User::where('parent_id', parentId())
                            ->whereNotIn('type', ['customer', 'agent']);

                // If a company is selected, filter by company_id
                if ($request->filled('company_id')) {
                    $users->where('company_id', $request->company_id);
                }

                $users = $users->get();

                return view('user.index', compact('users', 'companies'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



    public function create()
    {
        $userRoles = Role::where('parent_id', parentId())->whereNotIn('name', ['customer', 'agent'])->get()->pluck('name', 'id');
        // Get all companies for dropdown
        $companies = InsuranceCompany::get()->pluck('name', 'id');
        return view('user.create', compact('userRoles','companies'));
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create user')) {
            if (\Auth::user()->type == 'super admin') {
                $validator = \Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                    'company_id' => 'required|array',
                    'company_id.*' => 'exists:insurance_companies,id',
                ]);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = \Hash::make($request->password);
                $user->phone_number = $request->phone_number;
                $user->type = 'owner';
                $user->company_id = implode(',', $request->company_id);
                $user->lang = 'english';
                $user->subscription = 1;
                $user->parent_id = parentId();
                $user->save();

                $userRole = Role::findByName('owner');
                $user->assignRole($userRole);

                defaultCustomerCreate($user->id);
                defaultAgentCreate($user->id);

                return redirect()->route('users.index')->with('success', __('User successfully created.'));
            } else {
                $validator = \Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                    'role' => 'required',
                    'company_id' => 'required|array',
                    'company_id.*' => 'exists:insurance_companies,id',
                ]);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $ids = parentId();
                $authUser = \App\Models\User::find($ids);
                $totalUser = $authUser->totalUser();
                $subscription = Subscription::find($authUser->subscription);

                // if ($totalUser >= $subscription->user_limit && $subscription->user_limit != 0) {
                //     return redirect()->back()->with('error', __('Your user limit is over, please upgrade your subscription.'));
                // }

                $userRole = Role::findById($request->role);

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone_number = $request->phone_number;
                $user->password = \Hash::make($request->password);
                $user->type = $userRole->name;
                $user->company_id = implode(',', $request->company_id); // Save as 1,2,3
                $user->profile = 'avatar.png';
                $user->lang = 'english';
                $user->parent_id = parentId();
                $user->save();

                $user->assignRole($userRole);

                return redirect()->route('users.index')->with('success', __('User successfully created.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $userRoles = Role::where('parent_id', '=', parentId())->whereNotIn('name', ['customer', 'agent'])->get()->pluck('name', 'id');
        // Get all companies for dropdown
        $companies = InsuranceCompany::get()->pluck('name', 'id');
        return view('user.edit', compact('user', 'userRoles','companies'));
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit user')) {
            $user = User::findOrFail($id);

            if (\Auth::user()->type == 'super admin') {
                $validator = \Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'company_id' => 'required|array',
                    'company_id.*' => 'exists:insurance_companies,id',
                ]);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone_number = $request->phone_number;
                $user->company_id = implode(',', $request->company_id);
                $user->save();

                return redirect()->route('users.index')->with('success', 'User successfully updated.');
            } else {
                $validator = \Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'role' => 'required',
                    'company_id' => 'required|array',
                    'company_id.*' => 'exists:insurance_companies,id',
                ]);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $userRole = Role::findById($request->role);

                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone_number = $request->phone_number;
                $user->type = $userRole->name;
                $user->company_id = implode(',', $request->company_id); // Save as "1,2,3"
                $user->save();
                $user->assignRole($userRole);

                return redirect()->route('users.index')->with('success', 'User successfully updated.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {

        if (\Auth::user()->can('delete user')) {
            $user = User::find($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', __('User successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function loggedHistory()
    {
        $ids = parentId();
        $authUser = \App\Models\User::find($ids);
        $subscription = \App\Models\Subscription::find($authUser->subscription);

        if (\Auth::user()->can('manage logged history') && $subscription->enabled_logged_history == 1) {
            $histories = LoggedHistory::where('parent_id', parentId())->get();
            return view('logged_history.index', compact('histories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function loggedHistoryShow($id)
    {
        if (\Auth::user()->can('manage logged history')) {
            $histories = LoggedHistory::find($id);
            return view('logged_history.show', compact('histories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function loggedHistoryDestroy($id)
    {
        if (\Auth::user()->can('delete logged history')) {
            $histories = LoggedHistory::find($id);
            $histories->delete();
            return redirect()->back()->with('success', 'Logged history succefully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


}
