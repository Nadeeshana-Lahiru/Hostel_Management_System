<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Student;

class RoomAllocationController extends Controller
{
    // Shows the list of hostels to start the allocation process
    public function index()
    {
        $hostels = Hostel::withCount('rooms')->get();
        return view('admin.allocations.index', compact('hostels'));
    }

    public function showHostelRooms(Hostel $hostel)
    {
        $roomsByFloor = $hostel->rooms()
                            ->withCount('students')
                            ->orderBy('room_number')
                            ->get()
                            ->groupBy('floor');

        return view('admin.allocations.show_hostel_rooms', compact('hostel', 'roomsByFloor'));
    }

    public function showAllocationForm(Room $room)
    {
        $currentStudents = $room->students;
        $unassignedStudents = Student::whereNull('room_id')->orderBy('full_name')->get();

        return view('admin.allocations.form', compact('room', 'currentStudents', 'unassignedStudents'));
    }

    public function assignStudent(Request $request, Room $room)
    {
        $request->validate(['student_id' => 'required|exists:students,id']);

        // Server-side check to prevent over-allocation
        if ($room->students()->count() >= $room->capacity) {
            return redirect()->back()->with('error', 'This room is already full!');
        }

        $student = Student::find($request->student_id);

        // Ensure student is not already assigned
        if ($student->room_id) {
            return redirect()->back()->with('error', 'This student is already assigned to another room!');
        }

        $student->room_id = $room->id;
        $student->save();

        return redirect()->route('admin.allocations.showAllocationForm', $room->id)
                        ->with('success', "{$student->full_name} has been assigned to Room {$room->room_number}.");
    }
}
