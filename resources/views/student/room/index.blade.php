@extends('student.layout')
@section('title', 'My Room')
@section('page-title', '') {{-- The title is now inside the content --}}

@section('content')
<style>
    /* Page Header */
    .page-header {
        margin-bottom: 20px;
    }
    .page-header h2 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }

    /* Table Styles (Copied from admin panel for consistency) */
    .table-container { 
        background-color: #fff; 
        padding: 1.5rem; 
        border-radius: 8px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    table { width: 100%; border-collapse: collapse; }
    th, td { 
        padding: 0.9rem; 
        border-bottom: 1px solid #e3e6f0; 
        text-align: left; 
        vertical-align: middle;
    }
    thead th { 
        background-color: #f8f9fc; 
        font-weight: 600; 
        color: #5a5c69;
        border-top: 1px solid #e3e6f0;
    }
    tbody tr:hover { background-color: #f8f9fc; }

    /* NEW STYLES FOR THE STUDENT NAME LINK */
    .student-name-link {
        font-weight: 600;
        color: #4e73df; /* Main theme color */
        text-decoration: none; /* Removes the underline */
        transition: color 0.2s ease-in-out; /* Smooth color change on hover */
    }
    .student-name-link:hover {
        color: #2e59d9; /* Darker shade on hover */
        text-decoration: underline; /* Adds underline ONLY on hover */
    }

    /* NEW STYLES */
    .fieldset-modern { border: 1px solid #e3e6f0; padding: 1.5rem 2rem; border-radius: 8px; margin-bottom: 2rem; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .legend-modern { font-weight: 600; font-size: 1.2rem; color: #4e73df; padding: 0 10px; }
    .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
    .filter-group label { font-weight: 600; margin-bottom: 5px; font-size: 0.9rem; color: #5a5c69; }
    .filter-group label.required:after { content: ' *'; color: #e74a3b; }
    .filter-group input, .filter-group select { padding: 10px; border-radius: 5px; border: 1px solid #ddd; width: 100%; }
    .filter-buttons { grid-column: 1 / -1; display: flex; gap: 10px; margin-top: 1rem; }
    .btn { padding: 10px 25px; border-radius: 5px; border: none; color: white; cursor: pointer; text-decoration: none; font-weight: 600; transition: all 0.2s; }
    .btn-primary { background-color: #4e73df; }
    .btn-secondary { background-color: #858796; }
    .btn:hover { transform: translateY(-2px); }

    .results-container {
        margin-top: 40px;
        max-width: 90%; /* Prevents it from touching the far left edge */
        margin-left: auto;
        margin-right: auto;
    }

    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }
    .room-box {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
    }
    .room-box:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .room-number {
        font-size: 1.25rem;
        display: block;
        margin-bottom: 5px;
    }
    .room-availability {
        font-size: 0.9rem;
        color: #858796;
    }
    .room-box.available { border-left: 5px solid #1cc88a; } /* Green */
    .room-box.occupied { border-left: 5px solid #f6c23e; } /* Yellow */
    .room-box.full { border-left: 5px solid #e74a3b; /* Red */ background-color: #f8f9fc; color: #858796; }
</style>

@php
    // This array translates the floor number from the database (0, 1, etc.) into a readable name.
    $floorNames = [
        0 => 'Ground Floor',
        1 => 'First Floor',
        2 => 'Second Floor',
        3 => 'Third Floor'
    ];
@endphp

@if(!$room)
    <h4>You have not been assigned to a room yet.</h4>
@else
    <div class="page-header">
        <h2>Your Room | Number {{ $room->room_number }} in {{ $floorNames[(int)$room->floor] ?? 'N/A' }}</h2>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Registration No</th>
                    <th>Faculty</th>
                    <th>Batch</th>
                </tr>
            </thead>
            <tbody>
                {{-- Your own details --}}
                <tr>
                    <td>
                        <a href="{{ route('student.room.showRoommate', $student->id) }}" class="student-name-link">
                            <strong>{{ $student->full_name }} (You)</strong>
                        </a>
                    </td>
                    <td>{{ $student->reg_no }}</td>
                    <td>{{ $student->faculty }}</td>
                    <td>{{ $student->batch }}</td>
                </tr>

                {{-- Your roommates' details --}}
                @forelse($roommates as $roommate)
                    <tr>
                        <td>
                            <a href="{{ route('student.room.showRoommate', $roommate->id) }}" class="student-name-link">
                                {{ $roommate->full_name }}
                            </a>
                        </td>
                        <td>{{ $roommate->reg_no }}</td>
                        <td>{{ $roommate->faculty }}</td>
                        <td>{{ $roommate->batch }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 1.5rem; font-style: italic;">You are the only one in this room.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

<fieldset class="fieldset-modern" style="margin-top: 40px;">
    <legend class="legend-modern">Find a Roommate</legend>
    <form action="{{ route('student.room.index') }}" method="GET" class="filter-form">
        <input type="hidden" name="find_roommate" value="1">
        <div class="filter-grid">
            <div class="filter-group"><label for="floor" class="required">Floor</label><select name="floor" required><option value="">Any</option><option value="0">Ground</option><option value="1">First</option><option value="2">Second</option><option value="3">Third</option></select></div>
            <div class="filter-group"><label for="faculty" class="required">Faculty</label><select name="faculty" required><option value="">Any</option>@foreach($faculties as $item)<option value="{{ $item->faculty }}" {{ request('faculty') == $item->faculty ? 'selected' : '' }}>{{ $item->faculty }}</option>@endforeach</select></div>
            <div class="filter-group"><label for="batch" class="required">Batch</label><select name="batch" required><option value="">Any</option>@foreach($batches as $item)<option value="{{ $item->batch }}" {{ request('batch') == $item->batch ? 'selected' : '' }}>{{ $item->batch }}</option>@endforeach</select></div>
            <div class="filter-group"><label for="year" class="required">Year</label><select name="year" required><option value="">Any</option><option value="1" {{ request('year') == '1' ? 'selected' : '' }}>1st Year</option><option value="2" {{ request('year') == '2' ? 'selected' : '' }}>2nd Year</option><option value="3">3rd Year</option><option value="4">4th Year</option></select></div>
            <div class="filter-group"><label for="religion" class="required">Religion</label><select name="religion" required><option value="">Any</option>@foreach($religions as $item)<option value="{{ $item->religion }}" {{ request('religion') == $item->religion ? 'selected' : '' }}>{{ $item->religion }}</option>@endforeach</select></div>
            <div class="filter-group"><label for="province">Province</label><select name="province"><option value="">Any</option>@foreach($provinces as $item)<option value="{{ $item->province }}" {{ request('province') == $item->province ? 'selected' : '' }}>{{ $item->province }}</option>@endforeach</select></div>
            <div class="filter-group"><label for="department">Department</label><select name="department"><option value="">Any</option>@foreach($departments as $item)<option value="{{ $item->department }}" {{ request('department') == $item->department ? 'selected' : '' }}>{{ $item->department }}</option>@endforeach</select></div>
            <div class="filter-group"><label for="course">Course</label><select name="course"><option value="">Any</option>@foreach($courses as $item)<option value="{{ $item->course }}" {{ request('course') == $item->course ? 'selected' : '' }}>{{ $item->course }}</option>@endforeach</select></div>
        </div>
        <div class="filter-buttons" style="margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Find Roommate</button>
            <a href="{{ route('student.room.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</fieldset>

@if(isset($foundRooms))
    <div class="results-container">
        <div class="page-header">
            <h2>Matching Rooms</h2>
        </div>
        @if($foundRooms->isEmpty())
            <div class="table-container" style="text-align: center;">
                <p>No rooms found with students matching your criteria.</p>
            </div>
        @else
            @foreach($foundRooms as $floor => $rooms)
                <div class="floor-section">
                    <h3 class="floor-title">{{ $floorNames[$floor] ?? "Floor {$floor}" }}</h3>
                    <div class="room-grid">
                        @foreach($rooms as $room)
                            <div class="room-box">
                                <span class="room-number">Room {{ $room->room_number }}</span>
                                <span class="room-availability">{{ $room->students_count }} / {{ $room->capacity }} Occupied</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endif
@endsection