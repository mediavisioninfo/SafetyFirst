<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoggedHistory;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user=\App\Models\User::find(1);
        \App::setLocale($user->lang);

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /*public function store(LoginRequest $request)
    {
        if(env('google_recaptcha') == 'on')
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }else{
            $validation = [];
        }
        $this->validate($request, $validation);

        $request->authenticate();
        $request->session()->regenerate();
        $loginUser = Auth::user();
        if($loginUser->is_active == 0)
        {
            auth()->logout();
        }
        userLoggedHistory();

        // return redirect()->intended(RouteServiceProvider::HOME);
        // Redirect to 2FA selection screen
        return redirect()->route('2fa.selection');
    }*/

    public function store(LoginRequest $request)
    {
        // Captcha validation if enabled
        if (env('google_recaptcha') == 'on') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);
        // Authenticate user
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        // If user is inactive, log them out
        if ($user->is_active == 0) {
            auth()->logout();
            return redirect()->back()->withErrors(['email' => 'Your account is inactive.']);
        }

        userLoggedHistory();

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save OTP in database
        $user->update([
            'two_factor_code' => $otp,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        // Send Email OTP
        if (!empty($user->email)) {
            Mail::send('auth.email-otp', [
                'name' => $user->name,
                'otp' => $otp
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your OTP Code - Safety First');
            });
        }

        // dd($user->phone_number);

        // Send SMS OTP
        if (!empty($user->phone_number)) {
            // dd($user->phone_number);
            $authKey = env('SMSCOUNTRY_AUTHKEY');
            $authToken = env('SMSCOUNTRY_AUTHTOKEN');
            $senderId = env('SMSCOUNTRY_SENDERID');

            $auth = base64_encode("$authKey:$authToken");

            // Define both numbers
            $numbers = [];

            // Normalize and add claim user mobile
            $mobile = $user->phone_number;
            if (substr($mobile, 0, 3) === '+91') {
                $mobile = substr($mobile, 3);
            } elseif (substr($mobile, 0, 1) === '0') {
                $mobile = substr($mobile, 1);
            }
            $numbers[] = '91' . $mobile;

            // Send SMS to each number
            foreach ($numbers as $number) {
                // dd($number);
                $data = [
                    "Text" => "Your SafetyFirst login OTP is $otp. Valid for 10 mins only. Do not share. Contact support at 7880112303.",
                    "Number" => $number,
                    "SenderId" => $senderId,
                    "TemplateId" => "1707175549693433115",
                    "Is_Unicode" => false
                ];

                $response = Http::withHeaders([
                    'Authorization' => "Basic $auth",
                    'Content-Type' => 'application/json'
                ])->post("https://restapi.smscountry.com/v0.1/Accounts/$authKey/SMSes", $data);

                $responseData = $response->json();

                if (!empty($responseData['Success'])) {
                    \Log::info("SMS successfully queued", [
                        'uuid' => $responseData['MessageUUID'],
                        'mobile' => $number
                    ]);
                } else {
                    \Log::error('SMSCountry failed', ['mobile' => $number, 'response' => $response->body()]);
                }
            }
        }

        // Redirect to OTP verification page
        return redirect()->route('2fa.verifyForm')
            ->with('success', 'An OTP has been sent to your registered email and mobile number.');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
