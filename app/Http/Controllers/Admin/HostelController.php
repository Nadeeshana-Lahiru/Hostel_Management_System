<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Room;   // Import Room model
use App\Models\Warden;  // Import Warden model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB for transactions

class HostelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relationships and count rooms for efficiency
        $hostels = Hostel::with('warden')->withCount('rooms')->get();
        return view('admin.hostels.index', compact('hostels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all wardens to populate the dropdown
        $wardens = Warden::all();
        return view('admin.hostels.create', compact('wardens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255|unique:hostels,name',
            'warden_id' => 'required|exists:wardens,id',
            'rooms_ground' => 'required|integer|min:0',
            'rooms_first' => 'required|integer|min:0',
            'rooms_second' => 'required|integer|min:0',
            'rooms_third' => 'required|integer|min:0',
        ]);

        $totalRooms = $request->rooms_ground + $request->rooms_first + $request->rooms_second + $request->rooms_third;
        if ($totalRooms < 1) {
            return redirect()->back()->withErrors(['rooms_ground' => 'At least one floor must have rooms.'])->withInput();
        }

        DB::beginTransaction();
        try {
            // Step 1: Create the Hostel, now with the calculated total rooms
            $hostel = Hostel::create([
                'name' => $request->name,
                'warden_id' => $request->warden_id,
                'number_of_rooms' => $totalRooms,
            ]);

            // --- NEW SEQUENTIAL ROOM CREATION LOGIC ---
            $roomCounter = 1; // Start room numbering at 1

            // Create rooms for Ground Floor (floor = 0)
            for ($i = 0; $i < $request->rooms_ground; $i++) {
                Room::create([
                    'hostel_id' => $hostel->id,
                    'room_number' => $roomCounter++,
                    'floor' => 0,
                    'capacity' => 4,
                ]);
            }

            // Create rooms for First Floor (floor = 1)
            for ($i = 0; $i < $request->rooms_first; $i++) {
                Room::create([
                    'hostel_id' => $hostel->id,
                    'room_number' => $roomCounter++,
                    'floor' => 1,
                    'capacity' => 4,
                ]);
            }

            // Create rooms for Second Floor (floor = 2)
            for ($i = 0; $i < $request->rooms_second; $i++) {
                Room::create([
                    'hostel_id' => $hostel->id,
                    'room_number' => $roomCounter++,
                    'floor' => 2,
                    'capacity' => 4,
                ]);
            }

            // Create rooms for Third Floor (floor = 3)
            for ($i = 0; $i < $request->rooms_third; $i++) {
                Room::create([
                    'hostel_id' => $hostel->id,
                    'room_number' => $roomCounter++,
                    'floor' => 3,
                    'capacity' => 4,
                ]);
            }
            // --- END OF NEW LOGIC ---

            DB::commit();
            return redirect()->route('admin.hostels.index')->with('success', 'Hostel and rooms created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create hostel. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Hostel $hostel, Request $request)
    {
        $roomsQuery = $hostel->rooms()
                            ->withCount('students')
                            ->orderBy('room_number');

        // Apply search filter for an EXACT room number
        if ($request->filled('search')) {
            $roomsQuery->where('room_number', $request->search); // MODIFIED LINE
        }

        // Apply floor filter if present
        if ($request->filled('floor') && $request->floor !== '') {
            $roomsQuery->where('floor', $request->floor);
        }
        
        $roomsByFloor = $roomsQuery->get()->groupBy('floor');

                // NEW: Get data for the warden assignment modal
        // 1. Find all warden IDs that are already assigned to a hostel
        $assignedWardenIds = Hostel::whereNotNull('warden_id')->pluck('warden_id');
        // 2. Get all wardens who are NOT in that list
        $availableWardens = Warden::whereNotIn('id', $assignedWardenIds)->get();

        // MODIFIED: Pass the new data to the view
        return view('admin.hostels.show', compact('hostel', 'roomsByFloor', 'availableWardens'));
    }

    /**
     * Display the details of a specific room, including its occupants.
     */
    public function showRoomDetails(Room $room)
    {
        // Get the students in this specific room
        $students = $room->students;
        
        // Return a new view with the room and its students
        return view('admin.hostels.show_room', compact('room', 'students'));
    }

    /**
     * NEW METHOD: Assign or update the warden for a specific hostel.
     */
    public function assignWarden(Request $request, Hostel $hostel)
    {
        $request->validate([
            'warden_id' => 'required|exists:wardens,id'
        ]);

        $hostel->warden_id = $request->warden_id;
        $hostel->save();

        return redirect()->route('admin.hostels.show', $hostel->id)->with('success', 'Warden has been assigned successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
