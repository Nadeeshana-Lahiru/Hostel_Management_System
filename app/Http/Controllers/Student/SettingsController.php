<?php

namespace App\Http\Controllers\Student;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use App\Models\Student; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule; 
use Illuminate\Validation\Rules\Password;

class SettingsController extends StudentBaseController
{
    public function index()
    {
        $student = Auth::user()->student; 
        return view('student.settings.index', compact('student'));
    }

    /**
     * Handles the AJAX request to send an OTP.
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // Security check: Ensures students can only request for their own logged-in account
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

        Session::put('otp_verified_settings', true);
        return response()->json(['message' => 'OTP verified successfully.']);
    }

    /**
     * Handles the final password update via AJAX.
     */
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

    public function showProfileForm()
    {
        $student = Auth::user()->student;
        return view('student.settings.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        // Validation rules based on your students table
        $request->validate([
            'initial_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nic' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($student->id ?? null)],
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'dob' => 'required|date',
            'telephone_number' => 'required|string|max:15',
            'province' => 'required|string',
            'district' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_mobile' => 'required|string|max:15',
        ]);

        $user->email = $request->email;
        $user->username = $request->email;
        $user->save();

        Student::updateOrCreate(
            ['user_id' => $user->id],
            $request->except(['_token', 'email'])
        );

        return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
    }
}