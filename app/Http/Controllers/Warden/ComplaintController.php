<?php

namespace App\Http\Controllers\Warden;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends WardenBaseController
{
    /**
     * Display a list of complaints from students in the warden's hostel.
     */
    public function index(Request $request)
    {
        // If the warden is not assigned to a hostel, show a specific view.
        if (!$this->hostel) {
            return view('warden.complaints.index_unassigned');
        }

        // Get the IDs of all students in the warden's hostel.
        $studentIdsInHostel = $this->hostel->rooms()->with('students')->get()->pluck('students.*.id')->flatten()->unique();

        // Start the query, scoped to only complaints from those students.
        $query = Complaint::whereIn('student_id', $studentIdsInHostel)
                          ->with('student.user')
                          ->latest();

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(15);

        return view('warden.complaints.index', compact('complaints'));
    }

    /**
     * Show the details of a specific complaint.
     */
    public function show(Complaint $complaint)
    {
        // Security Check: Ensure the complaint belongs to a student in the warden's hostel.
        if (is_null($complaint->student->room) || $complaint->student->room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.complaints.index')->with('error', 'You are not authorized to view this complaint.');
        }

        $complaint->load('student.user');
        return view('warden.complaints.show', compact('complaint'));
    }

    /**
     * Update the status and reply of a complaint.
     */
    public function update(Request $request, Complaint $complaint)
    {
        // Security Check
        if (is_null($complaint->student->room) || $complaint->student->room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.complaints.index')->with('error', 'You are not authorized to update this complaint.');
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'admin_reply' => 'nullable|string',
        ]);

        $complaint->update([
            'status' => $request->status,
            'admin_reply' => $request->admin_reply, // The column is named admin_reply, but it can be used by wardens too.
        ]);

        return redirect()->route('warden.complaints.index')->with('success', 'Complaint updated successfully.');
    }
}