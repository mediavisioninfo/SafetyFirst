<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function show()
    {
        if (!session()->has('login.id')) {
            return redirect()->route('login');
        }
        return view('auth.two-factor-login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = User::find(session('login.id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid session.']);
        }

        if ($user->verifyTwoFactorAuth($request->code)) {
            Auth::login($user);
            session()->forget('login.id');
            userLoggedHistory();
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors(['code' => 'The provided 2FA code is invalid.']);
    }
}
