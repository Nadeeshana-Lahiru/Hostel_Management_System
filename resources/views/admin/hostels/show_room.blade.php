@extends('admin.layout')

@section('title', 'Room ' . $room->room_number . ' Details')
@section('page-title', '')

@section('content')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .page-header h2 { margin: 0; font-size: 1.75rem; color: #333; }
    .btn-secondary {
        display: inline-block; padding: 0.6rem 1.2rem; font-weight: 600; font-size: 0.9rem;
        text-align: center; text-decoration: none; color: #fff; background-color: #858796;
        border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s;
    }
    .btn-secondary:hover { background-color: #717384; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    
    .table-container { background-color: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.9rem; border-bottom: 1px solid #e3e6f0; text-align: left; vertical-align: middle; }
    thead th { background-color: #f8f9fc; font-weight: 600; color: #5a5c69; border-top: 1px solid #e3e6f0; }
    tbody tr:hover { background-color: #f8f9fc; }
    .student-name-link { font-weight: 600; color: #4e73df; text-decoration: none; }
    .student-name-link:hover { text-decoration: underline; }
</style>

<div class="page-header">
    <h2>Room {{ $room->room_number }} Details</h2>
    <a href="{{ route('admin.hostels.show', $room->hostel->id) }}" class="btn-secondary">&larr; Back to Floor Plan</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Registration No</th>
                <th>Faculty</th>
                <th>Batch</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td>
                    <a href="{{ route('admin.students.show', $student->id) }}" class="student-name-link">
                        {{ $student->full_name }}
                    </a>
                </td>
                <td><strong>{{ $student->reg_no }}</strong></td>
                <td>{{ $student->faculty }}</td>
                <td>{{ $student->batch }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 2rem;">This room is currently empty.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection