<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Validate the form data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'user_type' => 'required|in:admin,warden,student',
        ]);

        $userType = $request->input('user_type');

        // --- NEW LOGIC FOR STUDENT LOGIN ---
        if ($userType === 'student') {
            // 1. Find the student by their registration number
            $student = Student::where('reg_no', $request->username)->first();

            // 2. Check if the student exists and the password is correct for their associated user account
            if ($student && Hash::check($request->password, $student->user->password)) {
                // 3. Log in the user associated with the student profile
                Auth::login($student->user);
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back!');
            }
        } 
        // --- EXISTING LOGIC FOR ADMIN & WARDEN ---
        else {
            $credentials = [
                'username' => $request->username, // This is the email for admin/warden
                'password' => $request->password,
                'role' => $userType,
            ];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                switch ($userType) {
                    case 'admin':
                        return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
                    case 'warden':
                        return redirect()->intended(route('warden.dashboard'))->with('success', 'Welcome back!');
                }
            }
        }

        // Authentication failed for any user type...
        return back()->with('error', 'The provided credentials do not match our records.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}