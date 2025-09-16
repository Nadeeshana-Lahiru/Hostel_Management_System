<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Room;

class RoomController extends StudentBaseController
{
    public function index(Request $request)
    {
        // ADDED: Get the logged-in student from the base controller
        $student = $this->student;
        
        $room = $student->room;

        if (!$room) {
            return view('student.room.unassigned');
        }

        // Get roommates (other students in the same room)
        $roommates = $room ? $room->students()->where('id', '!=', $student->id)->get() : collect();

        // --- NEW: Logic for "Find Roommate" ---
        $foundRooms = null; // Initialize as null
        
        // Check if the "Find Roommate" form was submitted
        if ($request->has('find_roommate')) {
        $query = Student::query()
            ->where('id', '!=', $student->id)
            ->whereNotNull('room_id');

        // Apply all filters from the form
        if ($request->filled('faculty')) { $query->where('faculty', $request->faculty); }
        if ($request->filled('batch')) { $query->where('batch', $request->batch); }
        if ($request->filled('year')) { $query->where('year', $request->year); }
        if ($request->filled('religion')) { $query->where('religion', $request->religion); }
        if ($request->filled('province')) { $query->where('province', $request->province); }
        if ($request->filled('department')) { $query->where('department', $request->department); }
        if ($request->filled('course')) { $query->where('course', $request->course); }
        if ($request->filled('floor') && $request->floor !== '') {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('floor', $request->floor);
            });
        }

            // Get the unique room IDs of the students who match the criteria
            $matchingRoomIds = $query->pluck('room_id')->unique();

            // Fetch the actual Room models to display the results
            $foundRooms = Room::whereIn('id', $matchingRoomIds)->withCount('students')->with('students')->get()->groupBy('floor');
        }

        // --- Data for the Filter Dropdowns ---
        $faculties = Student::select('faculty')->whereNotNull('faculty')->distinct()->orderBy('faculty')->get();
        $batches = Student::select('batch')->whereNotNull('batch')->distinct()->orderBy('batch')->get();
        $departments = Student::select('department')->whereNotNull('department')->distinct()->orderBy('department')->get();
        $courses = Student::select('course')->whereNotNull('course')->distinct()->orderBy('course')->get();
        $religions = Student::select('religion')->whereNotNull('religion')->distinct()->orderBy('religion')->get();
        $provinces = Student::select('province')->whereNotNull('province')->distinct()->orderBy('province')->get();

        return view('student.room.index', compact(
            'room', 'roommates', 'student', 'foundRooms', 
            'faculties', 'batches', 'departments', 'courses', 'religions', 'provinces'
        ));
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
