<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $credentials = $request->only('username', 'password');
        $userType = $request->input('user_type');

        // Add the role to the credentials check
        $credentials['role'] = $userType;

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $request->session()->regenerate();

            // Redirect based on user type
            switch ($userType) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'warden':
                    // TODO: Create warden dashboard route
                    // return redirect()->intended(route('warden.dashboard'));
                    return redirect()->intended(route('warden.dashboard')); 
                case 'student':
                    // TODO: Create student dashboard route
                    // return redirect()->intended(route('student.dashboard'));
                    return redirect()->intended(route('student.dashboard'));
            }
        }

        // Authentication failed...
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