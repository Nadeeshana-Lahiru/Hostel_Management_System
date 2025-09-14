<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Student::query();

        // Apply search filter if present
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('reg_no', 'like', "%{$searchTerm}%")
                  ->orWhere('nic', 'like', "%{$searchTerm}%");
            });
        }

        // Apply faculty filter if present
        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        // Apply batch filter if present
        if ($request->filled('batch')) {
            $query->where('batch', $request->batch);
        }

        // Apply floor filter if present
        // This requires a relationship query
        if ($request->filled('floor')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('floor', $request->floor);
            });
        }

        // Eager load relationships for efficiency and paginate the results
        $students = $query->with('user', 'room')->latest()->paginate(15);

        // Get distinct values for filter dropdowns
        $faculties = Student::select('faculty')->distinct()->orderBy('faculty')->get();
        $batches = Student::select('batch')->distinct()->orderBy('batch')->get();

        // Get the total count of all students
        $totalStudents = Student::count();

        return view('admin.students.index', compact('students', 'faculties', 'batches', 'totalStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // A simple validation for key fields
        $request->validate([
            'initial_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'nic' => 'required|string|max:20|unique:students,nic',
            'reg_no' => 'required|string|max:50|unique:students,reg_no|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'telephone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'faculty' => 'required|string',
            'batch' => 'required|string',
            'year' => 'required|integer',
            'guardian_name' => 'required|string',
            'guardian_mobile' => 'required|string|max:15',
            'guardian_relationship' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_number' => 'required|string|max:15',
            // Optional fields
            'nationality' => 'nullable|string',
            'religion' => 'nullable|string',
            'civil_status' => 'nullable|string',
            'district' => 'nullable|string',
            'province' => 'nullable|string',
            'gn_division' => 'nullable|string',
            'department' => 'nullable|string',
            'course' => 'nullable|string',
            'guardian_dob' => 'nullable|date',
            'medical_info' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create User for login
            $user = User::create([
                'username' => $request->reg_no, // Username is the Reg No
                'email' => $request->email,
                'password' => Hash::make($request->nic), // Password is the NIC
                'role' => 'student',
            ]);

            // Create Student with all details
            Student::create([
                'user_id' => $user->id,
                // Personal Info
                'nic' => $request->nic,
                'initial_name' => $request->initial_name,
                'full_name' => $request->full_name,
                'address' => $request->address,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'telephone_number' => $request->telephone_number,
                
                // Fields from your form that are not in the provided snippet
                'nationality' => $request->input('nationality', 'Sri Lankan'), // Add default if not in form
                'religion' => $request->input('religion', ''),
                'civil_status' => $request->input('civil_status', 'unmarried'),
                'district' => $request->input('district', ''),
                'province' => $request->input('province', ''),
                'gn_division' => $request->input('gn_division', ''),

                // Educational Info
                'reg_no' => $request->reg_no,
                'batch' => $request->batch,
                'faculty' => $request->faculty,
                'department' => $request->department,
                'course' => $request->input('course', ''), // Assuming course might not be there
                'year' => $request->year,
                
                // Parent/Guardian Info
                'guardian_name' => $request->guardian_name,
                'guardian_relationship' => $request->guardian_relationship,
                'guardian_dob' => $request->input('guardian_dob', null),
                'guardian_mobile' => $request->guardian_mobile,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_number' => $request->emergency_contact_number,

                // Medical Info
                'medical_info' => $request->medical_info,
            ]);

            DB::commit();

            // We will create the index page next
            return redirect()->route('admin.dashboard')->with('success', 'Student added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // You can log the error for debugging: Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to add student. Please check the details and try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Eager load the user relationship to get the email
        $student->load('user');
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        // UPDATED: Added all validation rules
        $validatedData = $request->validate([
            'initial_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($student->user_id)],
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'telephone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'faculty' => 'required|string',
            'batch' => 'required|string',
            'year' => 'required|integer',
            'guardian_name' => 'required|string',
            'guardian_mobile' => 'required|string|max:15',
            'guardian_relationship' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_number' => 'required|string|max:15',
            // NEW: Added validation for dropdowns
            'nationality' => 'nullable|string',
            'religion' => 'nullable|string',
            'civil_status' => 'nullable|string',
            'district' => 'nullable|string',
            'province' => 'nullable|string',
            'gn_division' => 'nullable|string',
            'department' => 'nullable|string',
            'course' => 'nullable|string',
            'guardian_dob' => 'nullable|date',
            'medical_info' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Update the associated User record
            $student->user->update([
                'email' => $validatedData['email'],
            ]);

            // Update the Student record
            $student->update($validatedData);

            DB::commit();

            return redirect()->route('admin.students.index')->with('success', 'Student details updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update student.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            // Because of our database setup (onDelete cascade),
            // deleting the user will automatically delete the student record.
            $student->user()->delete();
            return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.students.index')->with('error', 'Failed to delete student.');
        }
    }
}
