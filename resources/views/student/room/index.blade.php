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
</style>

@if(!$room)
    <h4>You have not been assigned to a room yet.</h4>
@else
    <div class="page-header">
        <h2>Your Room | Number {{ $room->room_number }}</h2>
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
@endsection