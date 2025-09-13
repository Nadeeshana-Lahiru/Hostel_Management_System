<?php

namespace App\Http\Controllers\Warden;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room; 

class HostelController extends Controller
{
    /**
     * Display a listing of the resource.
     * This will only show the hostel assigned to the logged-in warden.
     */
    public function index()
    {
        // Manual security check
        if (!Auth::check() || Auth::user()->role !== 'warden') {
            return redirect()->route('login.form')->with('error', 'Access Denied.');
        }

        // Get the logged-in warden
        $warden = Auth::user()->warden;

        // Find the hostel(s) assigned to this warden
        $hostels = Hostel::where('warden_id', $warden->id)
            ->withCount('rooms')
            ->get();

        return view('warden.hostels.index', compact('hostels'));
    }

    /**
     * Display the specified resource.
     * This will only show details if the warden is assigned to the requested hostel.
     */
    public function show(Hostel $hostel, Request $request) // MODIFIED: Added Request
    {
        // Manual security check
        if (!Auth::check() || Auth::user()->role !== 'warden') {
            return redirect()->route('login.form')->with('error', 'Access Denied.');
        }

        // Security check: Ensure the warden is assigned to this specific hostel
        $loggedInWarden = Auth::user()->warden;
        if ($hostel->warden_id !== $loggedInWarden->id) {
            return redirect()->route('warden.dashboard')->with('error', 'You are not authorized to view this hostel.');
        }

        // --- NEW FILTERING LOGIC ---
        // Start a base query for rooms in this specific hostel
        $roomsQuery = $hostel->rooms()
                              ->withCount('students')
                              ->orderBy('room_number');

        // Apply search filter for an EXACT room number
        if ($request->filled('search')) {
            $roomsQuery->where('room_number', $request->search);
        }

        // Apply floor filter if present
        if ($request->filled('floor') && $request->floor !== '') {
            $roomsQuery->where('floor', $request->floor);
        }
        
        // Get the filtered rooms and group them by floor for display
        $roomsByFloor = $roomsQuery->get()->groupBy('floor');
        // --- END OF NEW LOGIC ---

        return view('warden.hostels.show', compact('hostel', 'roomsByFloor'));
    }

    /**
     * Display the details of a specific room, including its occupants.
     */
    public function showRoomDetails(Room $room)
    {
        // Manual security check
        if (!Auth::check() || Auth::user()->role !== 'warden') {
            return redirect()->route('login.form')->with('error', 'Access Denied.');
        }

        // Security check: Ensure the room belongs to the warden's hostel
        $wardenHostel = Hostel::where('warden_id', Auth::user()->warden->id)->first();
        if (!$wardenHostel || $room->hostel_id !== $wardenHostel->id) {
            return redirect()->route('warden.dashboard')->with('error', 'You are not authorized to view this room.');
        }

        // Get the students in this room
        $students = $room->students;
        
        return view('warden.hostels.show_room', compact('room', 'students'));
    }

    // NOTE: create(), store(), edit(), update(), and destroy() methods are intentionally
    // omitted as wardens do not have permission to manage hostels.
}