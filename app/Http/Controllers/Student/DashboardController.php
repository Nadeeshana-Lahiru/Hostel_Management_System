<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class DashboardController extends StudentBaseController
{
    public function index()
    {
        // Get messages for students from both admins and wardens
        $messages = Message::where('recipient_type', 'student_only')
            ->orWhere('recipient_type', 'both')
            ->latest()
            ->get();

        return view('student.dashboard', compact('messages'));
    }
}
