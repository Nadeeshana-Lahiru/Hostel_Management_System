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
        // 1. Validate the incoming request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // 2. Check if the username is an email address
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            // --- ATTEMPT ADMIN/WARDEN LOGIN ---
            $credentials = [
                'username' => $username,
                'password' => $password,
            ];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                // Redirect based on the user's role
                if ($user->role === 'admin') {
                    return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
                } elseif ($user->role === 'warden') {
                    return redirect()->intended(route('warden.dashboard'))->with('success', 'Welcome back!');
                }
            }
        } else {
            // --- ATTEMPT STUDENT LOGIN (using Registration Number) ---
            $student = Student::where('reg_no', $username)->first();

            if ($student && Hash::check($password, $student->user->password)) {
                Auth::login($student->user);
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back!');
            }
        }

        // 3. If all login attempts fail, return with an error
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