<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function selection()
    {
        return view('auth.2fa-selection');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'method' => 'required|in:mobile,email',
            'value' => 'required'
        ]);

        $user = Auth::user();
        $otp = rand(100000, 999999);

        // Validate that the provided value matches the logged-in user's email or mobile
        if ($request->method === 'email') {
            if ($request->value !== $user->email) {
                return back()->withErrors(['value' => 'This email does not match your account email.']);
            }
        } elseif ($request->method === 'mobile') {
            if ($request->value !== $user->mobile_number) {
                return back()->withErrors(['value' => 'This mobile number does not match your account mobile number.']);
            }
        }

        // Save OTP in DB
        $user->update([
            'two_factor_code' => $otp,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        // Send Email
        if ($request->method === 'email') {
            Mail::send('auth.email-otp', [
                'name' => $user->name,
                'otp' => $otp
            ], function ($message) use ($request) {
                $message->to($request->value)
                    ->subject('Your OTP Code');
            });
        }

        // Send SMS
        // if ($request->method === 'mobile') {
        //     $this->sendSmsCountryOtp($request->value, $otp);
        // }

        return redirect()->route('2fa.verifyForm')->with('success', 'OTP sent successfully!');
    }

    public function verifyForm()
    {
        return view('auth.2fa-verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $user = Auth::user();

        if ($user->two_factor_code === $request->code && $user->two_factor_expires_at->isFuture()) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

             // Set active company here
            $companyIds = explode(',', $user->company_id);

            if (!empty($companyIds)) {
                // Set first company as default
                session(['active_company_id' => $companyIds[0]]);
            }
            
            return redirect()->intended(\App\Providers\RouteServiceProvider::HOME);
        }

        return back()->withErrors(['code' => 'Invalid or expired code.']);
    }
}
