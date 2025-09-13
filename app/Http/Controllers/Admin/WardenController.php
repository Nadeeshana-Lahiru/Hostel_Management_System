<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWardenRequest; // Import the validation request
use App\Models\User;
use App\Models\Warden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;     // Import DB facade for transactions
use Illuminate\Support\Facades\Hash;    // Import Hash facade for passwords

class WardenController extends Controller
{
    // ... index, show, edit methods are here ...

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.wardens.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWardenRequest $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create a User record for login
            $user = User::create([
                'username' => $request->email,
                'email' => $request->email,
                'password' => Hash::make($request->nic), // Set initial password as NIC
                'role' => 'warden',
            ]);

            // Create a Warden record with the details
            Warden::create([
                'user_id' => $user->id,
                'initial_name' => $request->initial_name,
                'full_name' => $request->full_name,
                'nic' => $request->nic,
                'gender' => $request->gender,
                'address' => $request->address,
                'dob' => $request->dob,
                'nationality' => $request->nationality,
                'civil_status' => $request->civil_status,
                'district' => $request->district,
                'province' => $request->province,
                'telephone_number' => $request->telephone_number,
            ]);

            // If both are successful, commit the transaction
            DB::commit();

            return redirect()->route('admin.wardens.index')->with('success', 'Warden added successfully!');

        } catch (\Exception $e) {
            // If anything goes wrong, roll back the transaction
            DB::rollBack();
            // Optional: log the error
            // Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to add warden. Please try again.')->withInput();
        }
    }

        /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Warden::query();

        // If a search term is provided, filter the results
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            // Search in both full_name and nic columns
            $query->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('nic', 'like', "%{$searchTerm}%");
            });
        }

        // Eager load user relationship and get the results
        $wardens = $query->with('user')->latest()->get();

        // Get the total count of all wardens
        $totalWardens = Warden::count();
        
        return view('admin.wardens.index', compact('wardens', 'totalWardens'));
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Warden $warden)
    {
        // Eager load the user relationship to get the warden's email
        $warden->load('user');
        return view('admin.wardens.show', compact('warden'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warden $warden)
    {
        // The warden's data is automatically fetched by Laravel's route-model binding
        return view('admin.wardens.edit', compact('warden'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warden $warden)
    {
        // Validation rules for updating
        $request->validate([
            'initial_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            // Ensure the email is unique, but ignore the current user's email
            'email' => 'required|email|unique:users,email,' . $warden->user_id,
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'dob' => 'required|date',
            // ... add other fields as needed
        ]);

        DB::beginTransaction();
        try {
            // Step 1: Update the User model
            $user = $warden->user;
            $user->email = $request->email;
            $user->username = $request->email; // Keep username and email in sync
            $user->save();
            
            // Step 2: Update the Warden model
            $warden->update($request->except(['email'])); // Update all fields except email

            DB::commit();
            return redirect()->route('admin.wardens.index')->with('success', 'Warden details updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update warden. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warden $warden)
    {
        // Because we set up our database with 'onDelete('cascade')' for the warden's user_id,
        // we only need to delete the user. The database will automatically delete the
        // corresponding warden record.
        try {
            $warden->user()->delete();
            return redirect()->route('admin.wardens.index')->with('success', 'Warden deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.wardens.index')->with('error', 'Failed to delete warden.');
        }
    }
}