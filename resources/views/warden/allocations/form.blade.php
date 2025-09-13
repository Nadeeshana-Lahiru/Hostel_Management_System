@extends('warden.layout')

@section('title', 'Assign Student to Room ' . $room->room_number)
@section('page-title', 'Assign Student to Room ' . $room->room_number)

@section('content')
<style>
    /* Page Layout & General Styles */
    .page-container { max-width: 1100px; margin: auto; }
    .page-actions { margin-bottom: 25px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch; }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
    
    /* Fieldset & Legend Styles */
    .fieldset-modern { border: 1px solid #e3e6f0; padding: 1.5rem 2rem; border-radius: 8px; margin-bottom: 0; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; }
    .legend-modern { font-weight: 600; font-size: 1.2rem; color: #4e73df; padding: 0 10px; }

    /* Occupants List Styles */
    .occupants-list { list-style-type: none; padding: 0; flex-grow: 1; }
    .occupant-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #e3e6f0; }
    .occupant-item:last-child { border-bottom: none; }
    .occupant-info { display: flex; align-items: center; gap: 15px; }
    .occupant-info strong { color: #333; }
    .occupant-info span { color: #858796; font-size: 0.9rem; }

    /* Form Elements */
    .form-group label { font-weight: 600; margin-bottom: 0.5rem; color: #5a5c69; display: block; }
    .form-group select { width: 100%; padding: 0.75rem; border-radius: 5px; border: 1px solid #d1d3e2; box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s; }
    .form-group select:focus { outline: none; border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25); }

    /* General Button Styles */
    .btn { display: inline-block; padding: 0.6rem 1.2rem; font-weight: 600; font-size: 0.9rem; text-align: center; text-decoration: none; color: #fff; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s; border: none; cursor: pointer; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .btn-secondary { background-color: #858796; }
    .btn-primary { background-color: #4e73df; }
    .btn-warning { background-color: #f6c23e; }
    .btn-submit { background-color: #1cc88a; width: 100%; margin-top: 1rem; padding: 12px 20px; }

        /* Informational Messages */
    .info-message, .error-message { text-align: center; padding: 2rem; color: #858796; font-style: italic; margin: auto; }
    .error-message { color: #e74a3b; font-weight: 600; }
    
    /* --- COMPLETE BUTTON STYLES --- */
    .btn {
        display: inline-block; padding: 0.6rem 1.2rem; font-weight: 600; font-size: 0.9rem; text-align: center;
        text-decoration: none; color: #fff; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s; border: none; cursor: pointer;
    }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .btn-secondary { background-color: #858796; }
    .btn-warning { background-color: #f6c23e; }
    .btn-submit {
        background-color: #1cc88a; width: 100%; margin-top: 1rem; padding: 12px 20px;
        font-weight: 600; transition: all 0.2s;
    }
    .btn-submit:hover { background-color: #17a673; }

    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 15% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 8px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .modal-content h3 { margin-top: 0; font-size: 1.5rem; color: #333; }
    .modal-content p { margin-bottom: 1.5rem; color: #5a5c69; font-size: 1rem; line-height: 1.5; }
    .modal-buttons { display: flex; gap: 1rem; justify-content: center; }
    .modal-buttons .btn { flex-grow: 1; max-width: 150px; }
</style>

<div class="page-container">
    <div class="page-actions">
        <a href="{{ route('warden.allocations.showHostelRooms', $room->hostel->id) }}" class="btn btn-secondary">&larr; Back to Room Selection</a>
    </div>
    <div class="form-grid">
        <fieldset class="fieldset-modern">
            <legend class="legend-modern">Current Occupants ({{ $currentStudents->count() }} / {{ $room->capacity }})</legend>
            @if($currentStudents->isEmpty())
                <p class="info-message">This room is currently empty.</p>
            @else
                <ul class="occupants-list">
                    @foreach($currentStudents as $student)
                        <li class="occupant-item">
                            <div class="occupant-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#858796" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <div>
                                    <strong>{{ $student->full_name }}</strong><br>
                                    <span>{{ $student->reg_no }}</span>
                                </div>
                            </div>
                            <button class="btn btn-warning change-room-btn" data-student-id="{{ $student->id }}" data-student-name="{{ $student->full_name }}">Change Room</button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </fieldset>

        <fieldset class="fieldset-modern" @if($currentStudents->count() >= $room->capacity) disabled @endif>
            <legend class="legend-modern">Assign a New Student</legend>
            @if($currentStudents->count() >= $room->capacity)
                <p class="error-message">This room is full. Cannot assign more students.</p>
            @elseif($unassignedStudents->isEmpty())
                <p class="info-message">There are no unassigned students available to allocate.</p>
            @else
                <form action="{{ route('warden.allocations.assignStudent', $room->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="student_id">Select an Unassigned Student</label>
                        <select name="student_id" id="student_id" required>
                            <option value="">Select Student...</option>
                            @foreach($unassignedStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->reg_no }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-submit">Assign to Room</button>
                </form>
            @endif
        </fieldset>
    </div>
</div>

<div id="confirmRedirectModal" class="modal">
    <div class="modal-content">
        <h3 id="confirmRedirectModalTitle">Confirm Room Change</h3>
        <p>This will take you to the floor plan to select a new room. Continue?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary close-modal-btn">Cancel</button>
            <a href="#" id="confirm-redirect-btn" class="btn btn-primary">Yes, Continue</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const redirectModal = document.getElementById('confirmRedirectModal');
    const redirectModalTitle = document.getElementById('confirmRedirectModalTitle');
    const confirmRedirectBtn = document.getElementById('confirm-redirect-btn');
    const changeRoomBtns = document.querySelectorAll('.change-room-btn');
    
    changeRoomBtns.forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.dataset.studentId;
            const studentName = this.dataset.studentName;
            
            redirectModalTitle.innerText = `Confirm room change for ${studentName}?`;
            confirmRedirectBtn.href = `{{ route('warden.allocations.showHostelRooms', $room->hostel->id) }}?reassign_student_id=${studentId}`;
            
            redirectModal.style.display = 'block';
        });
    });

    document.querySelectorAll('.close-modal-btn').forEach(btn => {
        btn.onclick = function() { redirectModal.style.display = 'none'; }
    });
    window.onclick = function(event) { if (event.target == redirectModal) { redirectModal.style.display = 'none'; } }
});
</script>
@endpush