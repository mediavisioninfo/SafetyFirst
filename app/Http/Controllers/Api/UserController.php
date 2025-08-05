<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
   public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    // 2. Show users by role name
    public function getUsersByRole($roleName)
    {
        // Find role by name (e.g., "admin", "user", etc.)
        $role = Role::where('name', $roleName)->first();

        // dd($role);
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.',
            ], 404);
        }

        // Get all users with that role_id
        $users = User::where('type', $role->name)->with('role')->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
}
