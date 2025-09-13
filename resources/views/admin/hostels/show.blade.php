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

    /* --- NEW MODAL STYLES --- */
    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
    }
    .modal-content {
        background-color: #fefefe; margin: 10% auto; padding: 25px; border-radius: 8px;
        width: 90%; max-width: 600px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; margin-bottom: 20px; border-bottom: 1px solid #e3e6f0; }
    .modal-header h3 { margin: 0; }
    .current-warden { background-color: #f8f9fc; padding: 1rem; border-radius: 5px; margin-bottom: 1.5rem; }
    .warden-table-container { max-height: 250px; overflow-y: auto; margin-top: 1rem; border: 1px solid #e3e6f0; border-radius: 5px; }
    .warden-table { width: 100%; border-collapse: collapse; }
    .warden-table th, .warden-table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #e3e6f0; }
    .warden-table thead { position: sticky; top: 0; background-color: #f8f9fc; }
    .warden-table tbody tr:hover { background-color: #f1f3f8; }
    /* --- END OF NEW STYLES --- */

    /* --- NEW & COMPLETE BUTTON STYLES --- */
    .btn {
        display: inline-block; padding: 10px 20px; font-weight: 600; font-size: 0.9rem;
        text-align: center; text-decoration: none; color: white; border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s; border: none; cursor: pointer;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn-secondary { background-color: #858796; }
    .btn-warning { background-color: #f6c23e; color: #fff; } /* Yellow */
    /* --- END OF NEW STYLES --- */
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
        <button type="button" id="wardenInfoBtn" class="btn btn-warning">Warden Info</button>
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

<div id="wardenModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Manage Warden Assignment</h3>
            <span style="cursor:pointer; font-size: 28px;" id="closeWardenModal">&times;</span>
        </div>
        
        <div class="current-warden">
            <strong>Currently Assigned Warden:</strong>
            <p style="margin-top: 5px;">{{ $hostel->warden->full_name ?? 'None' }}</p>
        </div>

        <form action="{{ route('admin.hostels.assignWarden', $hostel->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <label for="warden_id"><strong>Select a New Warden:</strong></label>
            <div class="warden-table-container">
                @if($availableWardens->isEmpty())
                    <p style="text-align: center; padding: 1rem;">No other available wardens.</p>
                @else
                    <table class="warden-table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>NIC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableWardens as $warden)
                            <tr>
                                <td><input type="radio" name="warden_id" value="{{ $warden->id }}" required></td>
                                <td>{{ $warden->full_name }}</td>
                                <td>{{ $warden->nic }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <button type="button" id="cancelAssign" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-warning" @if($availableWardens->isEmpty()) disabled @endif>Assign</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('wardenModal');
    const openBtn = document.getElementById('wardenInfoBtn');
    const closeBtn = document.getElementById('closeWardenModal');
    const cancelBtn = document.getElementById('cancelAssign');

    openBtn.onclick = function() { modal.style.display = 'block'; }
    closeBtn.onclick = function() { modal.style.display = 'none'; }
    cancelBtn.onclick = function() { modal.style.display = 'none'; }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>
@endpush