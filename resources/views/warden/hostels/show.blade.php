@extends('warden.layout')

@section('title', 'Hostel Details')
@section('page-title')
    {{-- The route is now 'warden.hostels.index' --}}
    <a href="{{ route('warden.hostels.index') }}" style="text-decoration: none; color: #333;">My Hostel</a> / {{ $hostel->name }}
@endsection

@section('content')
<style>
    .page-actions { margin-bottom: 25px; }
    .btn-secondary {
        display: inline-block; padding: 0.6rem 1.2rem; font-weight: 600; font-size: 0.9rem;
        text-align: center; text-decoration: none; color: #fff; background-color: #858796;
        border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s;
    }
    .btn-secondary:hover { background-color: #717384; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }

    .filter-form {
        display: flex;
        gap: 15px;
        align-items: flex-end; /* Aligns items to the bottom, which looks nice with labels */
        background-color: #f8f9fc;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        border: 1px solid #e3e6f0;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    .filter-group label {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 0.9rem;
        color: #5a5c69;
    }
    .filter-group input, .filter-group select {
        width: 220px; /* Set a specific width to prevent stretching */
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 0.9rem;
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

    .floor-section { margin-bottom: 40px; }
    .floor-title { border-bottom: 2px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.5rem; color: #4e73df; }
    .room-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px; }
    .room-box { border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center; font-weight: bold; background-color: #fff; }
    .room-number { font-size: 1.25rem; display: block; margin-bottom: 5px; }
    .room-availability { font-size: 0.9rem; color: #858796; }
    .room-box.available { border-left: 5px solid #1cc88a; }
    .room-box.occupied { border-left: 5px solid #f6c23e; }
    .room-box.full { border-left: 5px solid #e74a3b; background-color: #f8f9fc; color: #858796; }

    .floor-header {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;
        border-bottom: 2px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px;
    }
    .floor-title { font-size: 1.5rem; color: #4e73df; margin: 0; }
    .occupancy-stats { display: flex; gap: 15px; font-size: 0.8rem; color: #858796; }
    .stat-item { background-color: #f8f9fc; padding: 5px 8px; border-radius: 5px; }
    .stat-item strong { color: #5a5c69; }
</style>

@php
    $floorNames = [0 => 'Ground Floor', 1 => 'First Floor', 2 => 'Second Floor', 3 => 'Third Floor'];
@endphp

<div class="page-actions">
    <a href="{{ route('warden.hostels.index') }}" class="btn-secondary">&larr; Back to My Hostel</a>
</div>

<form action="{{ route('warden.hostels.show', $hostel->id) }}" method="GET" class="filter-form">
    <div class="filter-group">
        <label for="search">Search by Room Number</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Enter exact room no...">
    </div>
    <div class="filter-group">
        <label for="floor">Filter by Floor</label>
        <select name="floor" id="floor">
            <option value="">All Floors</option>
            @foreach($floorNames as $floorNum => $floorName)
                <option value="{{ $floorNum }}" {{ request('floor') == (string)$floorNum ? 'selected' : '' }}>{{ $floorName }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label for="occupied">Filter by Occupied</label>
        <select name="occupied" id="occupied">
            <option value="">Any</option>
            <option value="0" {{ request('occupied') == '0' ? 'selected' : '' }}>0 Occupied (Empty)</option>
            <option value="1" {{ request('occupied') == '1' ? 'selected' : '' }}>1 Occupied</option>
            <option value="2" {{ request('occupied') == '2' ? 'selected' : '' }}>2 Occupied</option>
            <option value="3" {{ request('occupied') == '3' ? 'selected' : '' }}>3 Occupied</option>
            <option value="4" {{ request('occupied') == '4' ? 'selected' : '' }}>4 Occupied (Full)</option>
        </select>
    </div>
    <div class="filter-buttons">
        <button type="submit" class="btn-filter">Filter</button>
        <a href="{{ route('warden.hostels.show', $hostel->id) }}" class="btn-clear">Clear</a>
    </div>
</form>
@forelse($roomsByFloor as $floor => $rooms)
    <div class="floor-section">
        <div class="floor-header">
            <h2 class="floor-title">{{ $floorNames[$floor] ?? "Floor {$floor}" }}</h2>
            <div class="occupancy-stats">
                @for ($i = 0; $i <= 4; $i++)
                    <div class="stat-item">{{ $i }} Occupied: <strong>{{ $occupancyCountsByFloor[$floor][$i] ?? 0 }}</strong></div>
                @endfor
            </div>
        </div>
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
                <a href="{{ route('warden.hostels.showRoomDetails', $room->id) }}" style="text-decoration: none;">
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
    <p>No rooms have been generated for this hostel yet.</p>
@endforelse

@endsection