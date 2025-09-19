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
use Illuminate\Support\Facades\DB; 
use Illuminate\Validation\Rules\Password; 

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
        $user = User::where('email', $request->email)->where('id', Auth::id())->first();

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
            Session::put('otp_email_settings', $user->email);
            return response()->json(['message' => 'An OTP has been sent to your email.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not send OTP email. Please check configuration.'], 500);
        }
    }

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

        Session::put('otp_verified_settings', true);
        return response()->json(['message' => 'OTP verified successfully.']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ], [
            'password.min' => 'Password need to be minimum 8 characters.',
            'password.confirmed' => 'Passwords are not match.'
        ]);

        if (!Session::get('otp_verified_settings', false) || Session::get('otp_email_settings') !== Auth::user()->email) {
            return response()->json(['message' => 'Authentication failed. Please start over.'], 403);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        Session::forget(['otp_email_settings', 'otp_verified_settings']);

        Session::flash('success', 'You have successfully changed your password.');
        
        return response()->json(['message' => 'Password changed successfully!']);
    }
}