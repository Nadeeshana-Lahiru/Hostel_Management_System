<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SettingsController extends StudentBaseController
{
    public function index()
    {
        return view('student.settings.index');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->where('id', auth()->id())->first(); // Security check

        if (!$user) {
            return response()->json(['message' => 'Email does not match your account.'], 404);
        }

        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_email', $user->email);
        Session::put('otp_expires_at', now()->addMinutes(10));

        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
            return response()->json(['message' => 'OTP sent successfully to your email.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not send OTP email. Please check configuration.'], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if (!Session::has('otp') || Session::get('otp') != $request->otp || now()->gt(Session::get('otp_expires_at'))) {
            return response()->json(['message' => 'OTP is not Match or has expired.'], 400);
        }
        
        Session::put('otp_verified', true);
        return response()->json(['message' => 'OTP verified successfully.']);
    }

    public function changePassword(Request $request)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);

        if (!Session::get('otp_verified', false)) {
            return redirect()->route('student.settings.index')->with('error', 'Please verify OTP first.');
        }

        $user = User::where('email', Session::get('otp_email'))->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            Session::forget(['otp', 'otp_email', 'otp_expires_at', 'otp_verified']);

            // Redirect to student settings page
            return redirect()->route('student.settings.index')->with('success', 'Your password has been changed successfully!');
        }
        
        return redirect()->route('student.settings.index')->with('error', 'An unexpected error occurred.');
    }
}
