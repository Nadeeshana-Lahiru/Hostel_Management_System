<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Step 1: Show the form where the user enters their email
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Step 2: Validate email, generate OTP, and send it
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        // Check if the email exists in the database
        if (!$user) {
            return redirect()->back()->with('error', 'Email is Wrong.');
        }

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Delete any old tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Save the new OTP in the database (it's hashed for security)
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($otp),
            'created_at' => now(),
        ]);

        // Send the OTP to the user's real email address
        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not send OTP email. Please check your mail configuration.');
        }

        // Redirect to the form where they can enter the OTP
        return redirect()->route('password.reset')->with('email', $request->email);
    }

    // Step 3: Show the form to enter OTP and new password
    public function showResetForm(Request $request)
    {
        // We get the email from the previous step to pre-fill the form
        $email = session('email', old('email'));
        return view('auth.passwords.reset', ['email' => $email]);
    }

    // Step 4: Verify OTP and update the password
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find the most recent OTP request for this email
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)->latest()->first();

        // Check if a token exists and hasn't expired (default is 60 mins)
        if (!$tokenData || now()->subMinutes(60)->gt($tokenData->created_at)) {
            return redirect()->back()->with('error', 'Invalid or expired OTP.');
        }

        // Securely check if the entered OTP matches the hashed one in the database
        if (!Hash::check($request->otp, $tokenData->token)) {
            return redirect()->back()->with('error', 'OTP is not correct.');
        }

        // OTP is correct, find the user and update their password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Redirect to the login page with a success message
        return redirect()->route('login.form')->with('success', 'Your password has been changed successfully!');
    }
}