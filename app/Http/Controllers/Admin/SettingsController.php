<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index()
    {
 
        $user = Auth::user();
        $admin = $user->admin; // This uses the new relationship
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * NEW: Show the form for editing the admin's profile.
     */
    public function showProfileForm()
    {
        $admin = Auth::user()->admin;
        return view('admin.settings.profile', compact('admin'));
    }

    /**
     * NEW: Update the admin's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $request->validate([
            'initial_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            // Ensure email is unique, but ignore the current admin's user record
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nic' => ['required', 'string', 'max:20', Rule::unique('admins')->ignore($user->admin->id ?? null)],
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'dob' => 'required|date',
            'telephone' => 'required|string|max:15',
            'nationality' => 'required|string',
            'civil_status' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
        ]);

        // Update the User model (for email)
        $user->email = $request->email;
        $user->username = $request->email; // Keep username and email in sync
        $user->save();

        // Update or Create the Admin profile details
        Admin::updateOrCreate(
            ['user_id' => $user->id], // Find the admin profile by user_id
            $request->except(['_token', 'email']) // Update with all other form data
        );

        return response()->json(['success' => true, 'message' => 'Your profile has been updated successfully!']);
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