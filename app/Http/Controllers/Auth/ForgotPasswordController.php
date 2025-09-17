<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Handles the AJAX request to send an OTP.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Your Email Address is Wrong.'], 422);
        }

        $otp = rand(100000, 999999);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($otp),
            'created_at' => now(),
        ]);

        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
            Session::put('otp_email', $user->email);
            return response()->json(['message' => 'An OTP has been sent to your email.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not send OTP email. Please check configuration.'], 500);
        }
    }

    /**
     * Handles the AJAX request to verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'email' => 'required|email',
        ]);

        $tokenData = DB::table('password_reset_tokens')->where('email', $request->email)->latest()->first();

        if (!$tokenData || now()->subMinutes(5)->gt($tokenData->created_at)) {
            return response()->json(['message' => 'OTP was expired, Resend the OTP.'], 422);
        }

        if (!Hash::check($request->otp, $tokenData->token)) {
            return response()->json(['message' => 'OTP is not correct.'], 422);
        }

        Session::put('otp_verified', true);
        return response()->json(['message' => 'OTP verified successfully.']);
    }

    /**
     * Handles the final password update.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ], ['password.min' => 'Password need to be minimum 8 characters.']);

        if (!Session::get('otp_verified', false) || !Session::has('otp_email')) {
            return redirect()->route('login.form')->with('error', 'Authentication failed. Please start over.');
        }

        $user = User::where('email', Session::get('otp_email'))->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('email', Session::get('otp_email'))->delete();
            Session::forget(['otp_email', 'otp_verified']);

            return redirect()->route('login.form')->with('success', 'You have successfully reset the password. Now log in with your new password.');
        }

        return redirect()->route('login.form')->with('error', 'An unexpected error occurred.');
    }
}