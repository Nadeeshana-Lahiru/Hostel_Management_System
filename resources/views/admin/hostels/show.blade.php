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

    /* --- NEW: Floor Header with Occupancy Counts --- */
    .floor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .floor-title { font-size: 1.5rem; color: #4e73df; margin: 0; }
    .occupancy-stats { display: flex; gap: 15px; font-size: 0.8rem; color: #858796; }
    .stat-item { background-color: #f8f9fc; padding: 5px 8px; border-radius: 5px; }
    .stat-item strong { color: #5a5c69; }
    /* --- END OF NEW STYLES --- */

    /* --- NEW MODAL STYLES --- */
    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background-color: #ffffff; margin: 8% auto; padding: 30px; border-radius: 12px;
        width: 90%; max-width: 650px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        animation: slideDown 0.3s ease-out;
        font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    @keyframes slideDown {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header { 
        display: flex; justify-content: space-between; align-items: center; 
        padding-bottom: 15px; margin-bottom: 25px; 
        border-bottom: 2px solid #f1f3f8; 
    }
    .modal-header h3 { margin: 0; color: #2c3e50; font-size: 1.5rem; font-weight: 700; }
    
    .close-btn {
        color: #a0aec0; font-size: 28px; font-weight: bold; cursor: pointer;
        transition: color 0.2s; background: none; border: none; padding: 0; line-height: 1;
    }
    .close-btn:hover { color: #e53e3e; }

    .current-warden { 
        background: linear-gradient(135deg, #f6f8fd 0%, #f1f5f9 100%);
        padding: 1.25rem; border-radius: 8px; margin-bottom: 2rem; 
        border-left: 5px solid #4e73df;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .current-warden strong { color: #4a5568; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .current-warden p { margin: 8px 0 0; color: #2d3748; font-size: 1.2rem; font-weight: 600; }

    .warden-selection-label {
        display: block; font-weight: 600; color: #4a5568; margin-bottom: 10px; font-size: 1.05rem;
    }

    .warden-table-container { 
        max-height: 280px; overflow-y: auto; margin-top: 0.5rem; 
        border: 1px solid #e2e8f0; border-radius: 8px; 
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .warden-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .warden-table th, .warden-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #edf2f7; }
    .warden-table th { 
        position: sticky; top: 0; background-color: #f8fafc; 
        color: #4a5568; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;
        z-index: 10; border-bottom: 2px solid #e2e8f0;
    }
    .warden-table tbody tr { transition: all 0.2s ease; cursor: pointer; border-left: 4px solid transparent; }
    .warden-table tbody tr:hover { background-color: #f8fafc; }
    .warden-table tbody tr.selected { background-color: #ebf4ff; border-left: 4px solid #4e73df; }
    .warden-table tbody tr:last-child td { border-bottom: none; }
    
    .hidden-radio { opacity: 0; position: absolute; z-index: -1; width: 0; height: 0; }
    .check-icon {
        display: inline-block; width: 22px; height: 22px; border-radius: 50%;
        border: 2px solid #cbd5e0; position: relative; transition: all 0.2s;
        vertical-align: middle; margin-top: 2px;
    }
    .warden-table tbody tr:hover .check-icon { border-color: #a0aec0; }
    .warden-table tbody tr.selected .check-icon { background-color: #4e73df; border-color: #4e73df; transform: scale(1.1); }
    .warden-table tbody tr.selected .check-icon::after {
        content: ''; position: absolute; top: 2px; left: 6px;
        width: 5px; height: 10px; border: solid white;
        border-width: 0 2px 2px 0; transform: rotate(45deg);
    }

    .modal-actions {
        display: flex; justify-content: flex-end; gap: 15px; margin-top: 25px;
        padding-top: 20px; border-top: 1px solid #f1f3f8;
    }
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
        <a href="{{ route('admin.hostels.show', $hostel->id) }}" class="btn-clear">Clear</a>
        <button type="button" id="wardenInfoBtn" class="btn btn-warning">Warden Info</button>
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
            <span class="close-btn" id="closeWardenModal">&times;</span>
        </div>
        
        <div class="current-warden">
            <strong>Currently Assigned Warden</strong>
            <p>{{ $hostel->warden->full_name ?? 'None' }}</p>
        </div>

        <form action="{{ route('admin.hostels.assignWarden', $hostel->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <label for="warden_id" class="warden-selection-label">Select a New Warden</label>
            <div class="warden-table-container">
                @if($availableWardens->isEmpty())
                    <p style="text-align: center; padding: 2rem; color: #718096; font-style: italic;">No other available wardens.</p>
                @else
                    <table class="warden-table">
                        <thead>
                            <tr>
                                <th style="width: 60px; text-align: center;">Select</th>
                                <th>Name</th>
                                <th>NIC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableWardens as $warden)
                            <tr onclick="selectWardenRow(this)">
                                <td style="text-align: center;">
                                    <input type="radio" class="hidden-radio" name="warden_id" value="{{ $warden->id }}" required>
                                    <span class="check-icon"></span>
                                </td>
                                <td>{{ $warden->full_name }}</td>
                                <td>{{ $warden->nic }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="modal-actions">
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

function selectWardenRow(row) {
    const radio = row.querySelector('input[type=radio]');
    
    if (row.classList.contains('selected')) {
        // Deselect if already selected
        row.classList.remove('selected');
        radio.checked = false;
    } else {
        // Deselect all rows
        document.querySelectorAll('.warden-table tbody tr').forEach(r => {
            r.classList.remove('selected');
            r.querySelector('input[type=radio]').checked = false;
        });
        
        // Select the clicked row
        row.classList.add('selected');
        radio.checked = true;
    }
}
</script>
@endpush