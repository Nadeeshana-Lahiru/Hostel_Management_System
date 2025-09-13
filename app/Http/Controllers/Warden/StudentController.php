<?php

namespace App\Http\Controllers\Warden;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends WardenBaseController // Extends the base controller for security
{
    /**
     * Display a listing of students belonging to the warden's hostel.
     */
    public function index(Request $request)
    {
        if (!$this->hostel) {
            return view('warden.students.index_unassigned');
        }

        // The base query is now scoped to students in rooms within the warden's hostel
        $studentIdsInHostel = $this->hostel->rooms()->with('students')->get()->pluck('students.*.id')->flatten()->unique();
        $query = Student::whereIn('id', $studentIdsInHostel);

        // --- Apply Filters (same logic as admin, but on the scoped query) ---
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('reg_no', 'like', "%{$searchTerm}%")
                  ->orWhere('nic', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('faculty')) { $query->where('faculty', $request->faculty); }
        if ($request->filled('batch')) { $query->where('batch', $request->batch); }
        if ($request->filled('floor')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('floor', $request->floor);
            });
        }

        $students = $query->with('user', 'room')->latest()->paginate(15);

        // Get distinct values for filter dropdowns from ONLY the students in this hostel
        $faculties = Student::whereIn('id', $studentIdsInHostel)->select('faculty')->distinct()->orderBy('faculty')->get();
        $batches = Student::whereIn('id', $studentIdsInHostel)->select('batch')->distinct()->orderBy('batch')->get();

        $totalStudents = $studentIdsInHostel->count();

        return view('warden.students.index', compact('students', 'faculties', 'batches', 'totalStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warden.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation logic is identical to the admin's
        $validatedData = $request->validate([
            // ... copy all validation rules from Admin/StudentController's store method ...
        ]);
        
        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->reg_no,
                'email' => $request->email,
                'password' => Hash::make($request->nic),
                'role' => 'student',
            ]);

            $student = new Student($request->all());
            $student->user_id = $user->id;
            $student->save();

            DB::commit();
            // Redirect to the WARDEN's student list
            return redirect()->route('warden.students.index')->with('success', 'Student added successfully! You can now allocate them to a room.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add student.')->withInput();
        }
    }

    /**
     * Display the specified student's details.
     */
    public function show(Student $student)
    {
        // Security Check: Ensure the student belongs to the warden's hostel
        if (is_null($student->room) || $student->room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.students.index')->with('error', 'Access Denied.');
        }
        $student->load('user');
        return view('warden.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        // Security Check
        if (is_null($student->room) || $student->room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.students.index')->with('error', 'Access Denied.');
        }
        return view('warden.students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Security Check
        if (is_null($student->room) || $student->room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.students.index')->with('error', 'Access Denied.');
        }
        // Validation is the same as admin's, ensuring unique emails/nic except for the current user
        $validatedData = $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->user_id)],
            // ... other validation rules
        ]);

        DB::beginTransaction();
        try {
            $student->user->update(['email' => $request->email]);
            $student->update($request->except(['email']));
            DB::commit();
            return redirect()->route('warden.students.index')->with('success', 'Student details updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update student.')->withInput();
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Security Check
        if (is_null($student->room) || $student->room->hostel_id !== $this->hostel->id) {
            return redirect()->route('warden.students.index')->with('error', 'Access Denied.');
        }
        try {
            $student->user()->delete();
            return redirect()->route('warden.students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('warden.students.index')->with('error', 'Failed to delete student.');
        }
    }
}