<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Room;

class RoomController extends StudentBaseController
{
    public function index()
    {
        // ADDED: Get the logged-in student from the base controller
        $student = $this->student;
        
        $room = $student->room;

        if (!$room) {
            return view('student.room.unassigned');
        }

        // Get roommates (other students in the same room)
        $roommates = $room->students()->where('id', '!=', $student->id)->get();

        // UPDATED: Pass the '$student' variable to the view
        return view('student.room.index', compact('room', 'roommates', 'student'));
    }

    public function showRoommate(Student $student)
    {
        // Security check: ensure the requested student is a roommate
        $currentUserRoomId = $this->student->room_id;
        if (!$currentUserRoomId || $student->room_id !== $currentUserRoomId) {
            return redirect()->route('student.dashboard')->with('error', 'Access Denied.');
        }
        $student->load('user'); // Load email for display
        return view('student.room.show', compact('student'));
    }
}
