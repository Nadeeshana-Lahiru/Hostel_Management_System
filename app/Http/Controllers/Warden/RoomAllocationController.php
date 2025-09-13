<?php

namespace App\Http\Controllers\Warden;

use App\Models\Hostel;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;

class RoomAllocationController extends WardenBaseController
{
    /**
     * Show the hostel assigned to the warden to begin allocation.
     */
    public function index()
    {
        if (!$this->hostel) {
            // A view for when the warden isn't assigned to a hostel.
            return view('warden.allocations.index_unassigned');
        }

        // We pass the hostel in an array so the view's @forelse loop works without changes.
        $hostels = [$this->hostel];
        return view('warden.allocations.index', compact('hostels'));
    }

    /**
     * Show the rooms of the warden's hostel.
     */
    public function showHostelRooms(Hostel $hostel)
    {
        // Security Check: Ensure warden is accessing their own hostel.
        if ($hostel->id !== $this->hostel->id) {
            return redirect()->route('warden.dashboard')->with('error', 'Access Denied.');
        }

        $roomsByFloor = $hostel->rooms()
            ->withCount('students')
            ->orderBy('room_number')
            ->get()->groupBy('floor');

        return view('warden.allocations.show_hostel_rooms', compact('hostel', 'roomsByFloor'));
    }

    /**
     * Show the form to assign a student to a specific room.
     */
    public function showAllocationForm(Room $room)
    {
        // Security Check: Ensure the room is in the warden's hostel.
        if ($room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.dashboard')->with('error', 'Access Denied.');
        }

        $currentStudents = $room->students;
        $unassignedStudents = Student::whereNull('room_id')->orderBy('full_name')->get();

        // NEW: Get other available rooms in the same hostel for the "Change Room" modal
        $availableRooms = $this->hostel->rooms()
            ->where('id', '!=', $room->id)
            ->withCount('students')
            ->get()
            ->filter(function ($r) {
                return $r->students_count < $r->capacity;
            });

        return view('warden.allocations.form', compact('room', 'currentStudents', 'unassignedStudents', 'availableRooms'));
    }

    /**
     * Process the student assignment.
     */
    public function assignStudent(Request $request, Room $room)
    {
        // Security Check: Ensure the room is in the warden's hostel.
        if ($room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.dashboard')->with('error', 'Access Denied.');
        }
        
        $request->validate(['student_id' => 'required|exists:students,id']);

        if ($room->students()->count() >= $room->capacity) {
            return redirect()->back()->with('error', 'This room is already full!');
        }

        $student = Student::find($request->student_id);

        if ($student->room_id) {
             return redirect()->back()->with('error', 'This student is already assigned to another room!');
        }

        $student->room_id = $room->id;
        $student->save();

        // Redirect to the WARDEN's allocation form route
        return redirect()->route('warden.allocations.showAllocationForm', $room->id)
                         ->with('success', "{$student->full_name} has been assigned to Room {$room->room_number}.");
    }

    /**
     * NEW METHOD: Handles the final confirmation and re-assignment of a student.
     */
    public function confirmReassign(Request $request, Student $student, Room $new_room)
    {
        // Security Check 1: Ensure the student being moved is from the warden's hostel.
        if (is_null($student->room) || $student->room->hostel_id !== $this->hostel->id) {
            return redirect()->back()->with('error', 'You can only re-assign students from your hostel.');
        }
        
        // Security Check 2: Ensure the target room is also in the warden's hostel.
        if ($new_room->hostel_id !== $this->hostel->id) {
            return redirect()->back()->with('error', 'You can only re-assign students to rooms in your hostel.');
        }

        // Check capacity of the new room
        if ($new_room->students()->count() >= $new_room->capacity) {
            return redirect()->back()->with('error', 'The selected new room is already full!');
        }

        $originalRoomId = $student->room_id;
        $student->room_id = $new_room->id;
        $student->save();

        return redirect()->route('warden.allocations.showAllocationForm', $originalRoomId)
                         ->with('success', "{$student->full_name} has been moved to Room {$new_room->room_number}.");
    }
}