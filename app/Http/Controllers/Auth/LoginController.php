<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
     * NEW: Handle the initial AJAX request to check credentials without logging in.
     */
    public function checkCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $username = $request->input('username');
        $password = $request->input('password');

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('username', $username)->first();

            if (!$user) {
                // CORRECTED: Error message is now wrapped in an array
                return response()->json(['errors' => ['username' => ['This email is not registered.']]], 422);
            }

            if (!Hash::check($password, $user->password)) {
                // CORRECTED: Error message is now wrapped in an array
                return response()->json(['errors' => ['password' => ['The password you entered is incorrect.']]], 422);
            }
        } else {
            $student = Student::where('reg_no', $username)->first();

            if (!$student || !$student->user) {
                // CORRECTED: Error message is now wrapped in an array
                return response()->json(['errors' => ['username' => ['This registration number is not registered.']]], 422);
            }

            if (!Hash::check($password, $student->user->password)) {
                // CORRECTED: Error message is now wrapped in an array
                return response()->json(['errors' => ['password' => ['The password you entered is incorrect.']]], 422);
            }
        }

        return response()->json(['message' => 'Credentials are valid.'], 200);
    }


    /**
     * MODIFIED: Handle the final login request after the puzzle is solved.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // This part is now simplified because we know credentials are correct.
        // We just need to log the user in and redirect.

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            // Admin/Warden Login
            $credentials = ['username' => $username, 'password' => $password];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                if ($user->role === 'admin') {
                    return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
                } elseif ($user->role === 'warden') {
                    return redirect()->intended(route('warden.dashboard'))->with('success', 'Welcome back!');
                }
            }
        } else {
            // Student Login
            $student = Student::where('reg_no', $username)->first();
            if ($student && Hash::check($password, $student->user->password)) {
                Auth::login($student->user);
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back!');
            }
        }

        // This should theoretically not be reached if the flow is followed correctly
        return back()->with('error', 'An unexpected error occurred during login.');
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