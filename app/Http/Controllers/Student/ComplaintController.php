<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends StudentBaseController
{
    public function index()
    {
        $complaints = $this->student->complaints()->latest()->get();
        return view('student.complaints.index', compact('complaints'));
    }
    public function create() { return view('student.complaints.create'); }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'message' => 'required|string',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('complaints', 'public');
        }

        $this->student->complaints()->create([
            'type' => $validated['type'],
            'message' => $validated['message'],
            'image_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('student.complaints.index')->with('success', 'Your complaint has been submitted.');
    }
}
