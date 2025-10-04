@extends('student.layout')
@section('title', 'My Room')
@section('page-title', 'My Room') 

@section('content')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Get the new elements for date and time text.
    const dateText = document.getElementById('date-text');
    const timeText = document.getElementById('time-text');

    // Check if the elements exist on the page.
    if (dateText && timeText) {
        
        function updateClock() {
            const now = new Date();
            
            // Create a more beautiful, readable date format.
            // Example: Saturday, October 4, 2025
            const formattedDate = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Format the time. Example: 3:06:06 PM
            const formattedTime = now.toLocaleTimeString('en-US');

            // Update the text for both elements separately.
            dateText.textContent = formattedDate;
            timeText.textContent = formattedTime;
        }

        // Run once to show the time immediately.
        updateClock();
        
        // Update every second.
        setInterval(updateClock, 1000);
    }
});
</script>
@endpush

<style>
    .page-header {
        margin-bottom: 20px;
    }
    .page-header h2 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }

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

    .student-name-link {
        font-weight: 600;
        color: #4e73df; 
        text-decoration: none; 
        transition: color 0.2s ease-in-out; 
    }
    .student-name-link:hover {
        color: #2e59d9; 
        text-decoration: underline;
    }

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
        max-width: 90%; 
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

    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
    }
    .modal-content {
        background-color: #fefefe; margin: 15% auto; padding: 25px; border: none;
        width: 90%; max-width: 500px; border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s;
    }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; margin-bottom: 20px; border-bottom: 1px solid #e3e6f0; }
    .modal-header h3 { margin: 0; }
    .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-occupants-list { list-style-type: none; padding: 0; }
    .modal-occupant-item { display: flex; align-items: center; gap: 15px; padding: 1rem; border-bottom: 1px solid #e3e6f0; }
    .modal-occupant-item:last-child { border-bottom: none; }
</style>

@php
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
                            <button type="button" class="room-box view-occupants-btn"
                                    data-room-number="{{ $room->room_number }}"
                                    data-occupants="{{ $room->students->toJson() }}">
                                <span class="room-number">Room {{ $room->room_number }}</span>
                                <span class="room-availability">{{ $room->students_count }} / {{ $room->capacity }} Occupied</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endif

<div id="occupantsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="occupantsModalTitle">Room Occupants</h3>
            <span class="close-button">&times;</span>
        </div>
        <ul id="occupantsModalList" class="modal-occupants-list">
            </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('occupantsModal');
    const modalTitle = document.getElementById('occupantsModalTitle');
    const modalList = document.getElementById('occupantsModalList');
    const openBtns = document.querySelectorAll('.view-occupants-btn');
    const closeBtn = document.querySelector('#occupantsModal .close-button');

    openBtns.forEach(button => {
        button.addEventListener('click', function() {
            const roomNumber = this.dataset.roomNumber;
            const occupants = JSON.parse(this.dataset.occupants);

            // Update modal title
            modalTitle.innerText = `Occupants of Room ${roomNumber}`;

            // Clear previous list
            modalList.innerHTML = '';

            // Populate list with occupants
            if (occupants.length > 0) {
                occupants.forEach(student => {
                    const listItem = document.createElement('li');
                    listItem.className = 'modal-occupant-item';
                    listItem.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#858796" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <div>
                            <strong>${student.full_name}</strong><br>
                            <span>${student.reg_no}</span>
                        </div>
                    `;
                    modalList.appendChild(listItem);
                });
            } else {
                modalList.innerHTML = '<p>This room is currently empty.</p>';
            }
            
            // Show the modal
            modal.style.display = 'block';
        });
    });

    // Close modal logic
    closeBtn.onclick = function() { modal.style.display = 'none'; }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>
@endpush