@extends('admin.layout')

@section('title', 'Select a Room')
@section('page-title')
    <a href="{{ route('admin.allocations.index') }}" style="text-decoration: none; color: #333;">Room Allocation</a> / {{ $hostel->name }}
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
    .room-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px; }
    .room-box {
        border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center; font-weight: bold;
        background-color: #fff; border-left: 5px solid #ddd;
    }
    .room-box:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .room-number { font-size: 1.25rem; display: block; margin-bottom: 5px; }
    .room-availability { font-size: 0.9rem; color: #858796; }
    .room-box.available { border-left-color: #1cc88a; }
    .room-box.occupied { border-left-color: #f6c23e; }
    .room-box.full { border-left-color: #e74a3b; background-color: #f8f9fc; color: #858796; }

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

    .btn {
        display: inline-block; padding: 0.75rem 1.2rem; font-weight: 600; font-size: 0.9rem;
        text-align: center; text-decoration: none; color: white; border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s; border: none; cursor: pointer;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn-secondary { background-color: #858796; }
    .btn-primary { background-color: #4e73df; }
    /* --- END OF NEW STYLES --- */

    .page-actions { margin-bottom: 25px; }
    
    .alert-info {
        background-color: #cce5ff; color: #004085; border-color: #b8daff; padding: 1rem;
        margin-bottom: 1.5rem; border-radius: 5px; border: 1px solid; font-weight: 500;
    }

    /* Re-assignment Mode Styles */
    .reassign-mode .room-box.available, .reassign-mode .room-box.occupied { cursor: pointer; border-style: dashed; border-width: 2px; transition: all 0.2s ease-in-out; }
    .reassign-mode .room-box.available:hover, .reassign-mode .room-box.occupied:hover { border-color: #4e73df; }
    .reassign-mode .room-box.selected { border-style: solid; border-width: 3px; border-color: #4e73df; background-color: #eaecf4; transform: scale(1.05); box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4); }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); } 100% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); } }

        /* --- NEW & IMPROVED MODAL STYLES (same as above for consistency) --- */
    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
    }
    .modal-content {
        background-color: #fefefe; margin: 15% auto; padding: 25px; border: none;
        width: 90%; max-width: 450px; border-radius: 8px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s;
    }
    .modal-content h3 { margin-top: 0; font-size: 1.5rem; color: #333; }
    .modal-content p { margin-bottom: 1.5rem; color: #5a5c69; font-size: 1rem; }
    .modal-buttons { display: flex; gap: 1rem; justify-content: center; }
    .modal-buttons .btn { flex-grow: 1; max-width: 150px; }
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
    <a href="{{ route('admin.allocations.index') }}" class="btn-secondary">&larr; Back to Hostel Selection</a>
</div>

@if(request('reassign_student_id'))
    <div class="alert alert-info">
        <strong>Re-assignment Mode:</strong> Please select a new, available room for the student.
    </div>
@endif

@forelse($roomsByFloor as $floor => $rooms)
    <div class="floor-section">
        <h2 class="floor-title">{{ $floorNames[$floor] ?? "Floor {$floor}" }}</h2>
        <div class="room-grid">
            {{-- This is the full, correct code for the room loop --}}
            @foreach($rooms as $room)
                @php
                    $isFull = $room->students_count >= $room->capacity;
                    $availabilityClass = $isFull ? 'full' : ($room->students_count > 0 ? 'occupied' : 'available');
                @endphp
                
                @if(request('reassign_student_id') && !$isFull)
                    <button type="button" class="reassign-room-btn room-box {{ $availabilityClass }}" data-room-id="{{ $room->id }}" data-room-number="{{ $room->room_number }}">
                        <span class="room-number">Room {{ $room->room_number }}</span>
                        <span class="room-availability">{{ $room->students_count }} / {{ $room->capacity }} Occupied</span>
                    </button>
                @else
                    <a href="{{ route('admin.allocations.showAllocationForm', $room->id) }}" style="text-decoration: none;" @if($isFull) onclick="event.preventDefault();" @endif>
                        <div class="room-box {{ $availabilityClass }}" @if($isFull) style="cursor: not-allowed; opacity: 0.6;" @endif>
                            <span class="room-number">Room {{ $room->room_number }}</span>
                            <span class="room-availability">{{ $room->students_count }} / {{ $room->capacity }} Occupied</span>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endforeach

@if($roomsByFloor->isEmpty())
    <p>No rooms have been generated for this hostel yet.</p>
@endif

<div id="confirmAllocationModal" class="modal">
    <div class="modal-content">
        <h3 id="confirmAllocationModalTitle">Confirm the Allocation</h3>
        <p>Are you sure you want to move the student to this room?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary close-modal-btn">Cancel</button>
            <button type="submit" form="reassignForm" class="btn btn-primary">Yes, Confirm</button>
        </div>
    </div>
</div>

<form id="reassignForm" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const reassignStudentId = new URLSearchParams(window.location.search).get('reassign_student_id');
    
    if (reassignStudentId) {
        document.body.classList.add('reassign-mode');
        const modal = document.getElementById('confirmAllocationModal');
        const reassignForm = document.getElementById('reassignForm');
        const roomButtons = document.querySelectorAll('.reassign-room-btn');
        
        roomButtons.forEach(button => {
            button.addEventListener('click', function() {
                // NEW: Logic to handle the "selected" state
                // 1. Remove 'selected' from any previously selected button
                roomButtons.forEach(btn => btn.classList.remove('selected'));
                // 2. Add 'selected' to the button that was just clicked
                this.classList.add('selected');
                
                // Existing logic to set up the modal
                const newRoomId = this.dataset.roomId;
                reassignForm.action = `/admin/allocations/reassign-confirm/${reassignStudentId}/${newRoomId}`;
                modal.style.display = 'block';
            });
        });

        document.querySelectorAll('.close-modal-btn').forEach(btn => {
            btn.onclick = function() {
                // NEW: Also remove 'selected' class when modal is cancelled
                roomButtons.forEach(btn => btn.classList.remove('selected'));
                modal.style.display = 'none';
            }
        });
        window.onclick = function(event) {
            if (event.target == modal) {
                roomButtons.forEach(btn => btn.classList.remove('selected'));
                modal.style.display = 'none';
            }
        }
    }
});
</script>
@endpush