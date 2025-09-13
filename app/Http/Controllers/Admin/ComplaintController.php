<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with('student.user')->latest();

        // Filter by status if a status is provided in the URL
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(15);

        return view('admin.complaints.index', compact('complaints'));
    }

        public function show(Complaint $complaint)
    {
        // Eager load the student and their user info
        $complaint->load('student.user');
        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'admin_reply' => 'nullable|string',
        ]);

        $complaint->update([
            'status' => $request->status,
            'admin_reply' => $request->admin_reply,
        ]);

        return redirect()->route('admin.complaints.index')->with('success', 'Complaint updated successfully.');
    }
}