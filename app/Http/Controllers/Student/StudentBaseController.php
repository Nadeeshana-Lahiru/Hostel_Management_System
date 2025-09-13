<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentBaseController extends Controller
{
    protected $student;

    public function __construct()
    {
        // This is our manual security check for all student pages.
        if (!Auth::check() || Auth::user()->role !== 'student') {
            return redirect()->route('login.form')
                ->with('error', 'You do not have permission to access this page.')
                ->send();
        }

        // Get the student's profile and make it available to all child controllers
        $this->student = Auth::user()->student;
    }
}