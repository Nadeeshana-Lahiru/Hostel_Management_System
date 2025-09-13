@extends('warden.layout')

@section('title', 'Select a Room')
@section('page-title')
    <a href="{{ route('warden.allocations.index') }}" style="text-decoration: none; color: #333;">Room Allocation</a> / {{ $hostel->name }}
@endsection

@section('content')
<style>
    .floor-section {
        margin-bottom: 40px;
    }
    .floor-title {
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-size: 1.5rem;
        color: #4e73df;
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
    
    /* Color coding for availability */
    .room-box.available {
        border-left: 5px solid #1cc88a; /* Green */
    }
    .room-box.occupied {
        border-left: 5px solid #f6c23e; /* Yellow */
    }
    .room-box.full {
        border-left: 5px solid #e74a3b; /* Red */
        background-color: #f8f9fc;
        color: #858796;
    }

        .page-actions {
        margin-bottom: 25px;
    }
    .btn-secondary {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        text-decoration: none;
        color: #fff;
        background-color: #858796;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
    }
    .btn-secondary:hover {
        background-color: #717384;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
</style>

@php
    $floorNames = [
        0 => 'Ground Floor',
        1 => 'First Floor',
        2 => 'Second Floor',
        3 => 'Third Floor'
    ];
@endphp

<div class="page-actions">
    <a href="{{ route('warden.allocations.index') }}" class="btn-secondary">&larr; Back to Hostel Selection</a>
</div>

@foreach($roomsByFloor as $floor => $rooms)
    <div class="floor-section">
        <h2 class="floor-title">{{ $floorNames[$floor] ?? "Floor {$floor}" }}</h2>
        <div class="room-grid">
            @foreach($rooms as $room)
                @php
                    $availabilityClass = 'available'; // Default to green
                    if ($room->students_count > 0 && $room->students_count < $room->capacity) {
                        $availabilityClass = 'occupied'; // Yellow
                    } elseif ($room->students_count >= $room->capacity) {
                        $availabilityClass = 'full'; // Red
                    }
                @endphp
                
                <a href="{{ route('warden.allocations.showAllocationForm', $room->id) }}" style="text-decoration: none;">
                    <div class="room-box {{ $availabilityClass }}">
                        <span class="room-number">Room {{ $room->room_number }}</span>
                        <span class="room-availability">
                            {{ $room->students_count }} / {{ $room->capacity }} Occupied
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endforeach

@if($roomsByFloor->isEmpty())
    <p>No rooms have been generated for this hostel yet.</p>
@endif

@endsection