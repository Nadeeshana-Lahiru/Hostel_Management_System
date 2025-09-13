<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found in our database.'], 404);
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

        if (
            !Session::has('otp') ||
            Session::get('otp') != $request->otp ||
            now()->gt(Session::get('otp_expires_at'))
        ) {
            return response()->json(['message' => 'OTP is not Match or has expired.'], 400);
        }
        
        // If OTP is correct, set a flag that it has been verified
        Session::put('otp_verified', true);
        return response()->json(['message' => 'OTP verified successfully.']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the OTP was actually verified in this session
        if (!Session::get('otp_verified', false)) {
            return redirect()->route('admin.settings.index')->with('error', 'Please verify OTP first.');
        }

        $user = User::where('email', Session::get('otp_email'))->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Clean up session data
            Session::forget(['otp', 'otp_email', 'otp_expires_at', 'otp_verified']);

            return redirect()->route('admin.settings.index')->with('success', 'Your password has been changed successfully!');
        }
        
        return redirect()->route('admin.settings.index')->with('error', 'An unexpected error occurred.');
    }
}