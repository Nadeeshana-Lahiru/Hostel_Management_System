@extends('admin.layout')

@section('title', 'Assign Student to Room ' . $room->room_number)
@section('page-title', 'Assign Student to Room ' . $room->room_number)

@section('content')
<style>
    .page-container {
        max-width: 1100px;
        margin: auto;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        /* This ensures that grid items in a row stretch to the same height */
        align-items: stretch; 
    }
    /* Responsive layout for smaller screens */
    @media (max-width: 992px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
    .fieldset-modern {
        border: 1px solid #e3e6f0;
        padding: 1.5rem 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        /* Add these two lines to make the content inside the fieldset flexible */
        display: flex;
        flex-direction: column;
    }
    .legend-modern {
        font-weight: 600;
        font-size: 1.2rem;
        color: #4e73df;
        padding: 0 10px;
    }

    /* Current Occupants List */
    .occupants-list {
        list-style-type: none;
        padding: 0;
        flex-grow: 1; /* Allows the list to grow and push other content down */
    }
    .occupant-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 1rem;
        border-bottom: 1px solid #e3e6f0;
    }
    .occupant-item:last-child {
        border-bottom: none;
    }
    .occupant-item strong { color: #333; }
    .occupant-item span { color: #858796; font-size: 0.9rem; }

    /* Informational Messages */
    .info-message, .error-message {
        text-align: center;
        padding: 2rem;
        color: #858796;
        font-style: italic;
        margin: auto; /* Centers the message vertically */
    }
    .error-message {
        color: #e74a3b;
        font-weight: 600;
    }

        /* New Button Styles */
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

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #5a5c69;
        display: block;
    }
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #d1d3e2;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group select:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    .btn-submit {
        background-color: #1cc88a;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        width: 100%;
        margin-top: 1rem;
    }
    .btn-submit:hover {
        background-color: #17a673;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.allocations.showHostelRooms', $room->hostel->id) }}" class="btn-secondary">&larr; Back to Room Selection</a>
    </div>

<div class="page-container">
    <div class="form-grid">
        <fieldset class="fieldset-modern">
            <legend class="legend-modern">Current Occupants ({{ $currentStudents->count() }} / {{ $room->capacity }})</legend>
            @if($currentStudents->isEmpty())
                <p class="info-message">This room is currently empty.</p>
            @else
                <ul class="occupants-list">
                    @foreach($currentStudents as $student)
                        <li class="occupant-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#858796" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <div>
                                <strong>{{ $student->full_name }}</strong><br>
                                <span>{{ $student->reg_no }}</span>
                            </div>
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
                <form action="{{ route('admin.allocations.assignStudent', $room->id) }}" method="POST">
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
                    <button type="submit" class="btn-submit" style="width: 100%; margin-top: 1rem;">Assign to Room</button>
                </form>
            @endif
        </fieldset>
    </div>
</div>
@endsection