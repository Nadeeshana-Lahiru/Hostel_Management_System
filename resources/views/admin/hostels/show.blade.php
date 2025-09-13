@extends('admin.layout')

@section('title', 'Hostel Details')
@section('page-title')
    <a href="{{ route('admin.hostels.index') }}" style="text-decoration: none; color: #333;">Hostels</a> / {{ $hostel->name }}
@endsection

@section('content')
<style>
    /* New Button Styles */
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
    
    .floor-section { margin-bottom: 40px; }
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
    .room-box.available { border-left: 5px solid #1cc88a; } /* Green */
    .room-box.occupied { border-left: 5px solid #f6c23e; } /* Yellow */
    .room-box.full { border-left: 5px solid #e74a3b; /* Red */ background-color: #f8f9fc; color: #858796; }

    .filter-form {
        display: flex;
        gap: 15px;
        align-items: flex-end; /* Aligns items to the bottom, looks nice with labels */
        background-color: #f8f9fc;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    .filter-group label {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    /* Set specific widths to prevent stretching */
    .filter-group input { width: 200px; }
    .filter-group select { width: 200px; }
    .filter-group input, .filter-group select {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .filter-buttons {
        display: flex;
        gap: 10px;
    }
    .btn-filter, .btn-clear {
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        color: white;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
    }
    .btn-filter { background-color: #4e73df; }
    .btn-clear { background-color: #858796; }
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
    <a href="{{ route('admin.hostels.index') }}" class="btn-secondary">&larr; Back to All Hostels</a>
</div>

<form action="{{ route('admin.hostels.show', $hostel->id) }}" method="GET" class="filter-form">
    <div class="filter-group">
        <label for="search">Search by Room Number</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Enter room number...">
    </div>
    <div class="filter-group">
        <label for="floor">Filter by Floor</label>
        <select name="floor" id="floor">
            <option value="">All Floors</option>
            <option value="0" {{ request('floor') == '0' ? 'selected' : '' }}>Ground Floor</option>
            <option value="1" {{ request('floor') == '1' ? 'selected' : '' }}>First Floor</option>
            <option value="2" {{ request('floor') == '2' ? 'selected' : '' }}>Second Floor</option>
            <option value="3" {{ request('floor') == '3' ? 'selected' : '' }}>Third Floor</option>
        </select>
    </div>
    <div class="filter-buttons">
        <button type="submit" class="btn-filter">Filter</button>
        <a href="{{ route('admin.hostels.show', $hostel->id) }}" class="btn-clear">Clear</a>
    </div>
</form>
@forelse($roomsByFloor as $floor => $rooms)
    <div class="floor-section">
        <h2 class="floor-title">{{ $floorNames[$floor] ?? "Floor {$floor}" }}</h2>
        <div class="room-grid">
            @foreach($rooms as $room)
                @php
                    $availabilityClass = 'available';
                    if ($room->students_count > 0 && $room->students_count < $room->capacity) {
                        $availabilityClass = 'occupied';
                    } elseif ($room->students_count >= $room->capacity) {
                        $availabilityClass = 'full';
                    }
                @endphp
                <a href="{{ route('admin.hostels.showRoomDetails', $room->id) }}" style="text-decoration: none;">
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
@empty
    <div class="alert alert-info" style="text-align: center;">
        <p>No rooms found matching your criteria.</p>
    </div>
@endforelse

@endsection