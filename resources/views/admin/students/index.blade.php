@extends('admin.layout')

@section('title', 'All Students')
@section('page-title', 'Manage Students')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .filter-form {
        display: flex;
        flex-wrap: wrap; /* Allows items to wrap on smaller screens */
        align-items: flex-end; /* Aligns items to the bottom */
        gap: 20px;
        background-color: #f8f9fc;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .filter-group { display: flex; flex-direction: column; }
    .filter-group label { font-weight: 600; margin-bottom: 5px; font-size: 0.9rem; }
    .filter-group input, .filter-group select { padding: 8px; border-radius: 5px; border: 1px solid #ddd; }
    .filter-buttons { grid-column: 1 / -1; display: flex; gap: 10px; }
    .btn-filter { background-color: #4e73df; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    .btn-clear { background-color: #858796; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; }

        /* Table Styles */
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

    /* Action Buttons Styles */
    .actions .btn {
        padding: 0.5rem 0.6rem; /* Adjusted padding for a more square look */
        font-size: 0.8rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 5px;
        text-decoration: none;
        color: white;
        border: none;
        cursor: pointer;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease; /* Smooth transition for hover effect */
    }

    /* NEW: This class hides the text by default */
    .btn-text {
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        white-space: nowrap; /* Prevents text from wrapping during transition */
        padding-left: 0;
    }

    /* NEW: This makes the text appear when you hover over the button */
    .actions .btn:hover .btn-text {
        max-width: 100px; /* Needs to be wide enough for the text */
        opacity: 1;
        padding-left: 0.4rem; /* Adds a bit of space from the icon */
    }

    .actions-column {
        text-align: right;
    }

    .btn { padding: 10px 10px; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; }
    .btn-warning { background-color: #ffc107; }
    .btn-danger { background-color: #dc3545; }
    .btn-primary {
        background-color: #4e73df;
        padding: 10px 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-primary:hover {
        background-color: #2e59d9; /* Darker shade on hover */
        transform: translateY(-2px); /* Lifts the button up slightly */
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.4); /* Stronger shadow */
    }
    .btn-secondary { background-color: #858796; color: white; text-decoration: none; }
    .btn-info { background-color: #36b9cc; } /* NEW: Teal for Details */
    .btn-info:hover { background-color: #2a96a5; }
    .btn-warning { background-color: #f6c23e; }
    .btn-warning:hover { background-color: #dda20a; }
    .btn-danger { background-color: #e74a3b; }
    .btn-danger:hover { background-color: #be2617; }

    /* Pagination Styles */
    .pagination { justify-content: center; }

    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
    }
    .modal-content {
        background-color: #fefefe; margin: 15% auto; padding: 25px; border: 1px solid #888;
        width: 90%; max-width: 450px; border-radius: 8px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s;
    }
    @keyframes fadeIn { from {opacity: 0; transform: scale(0.95);} to {opacity: 1; transform: scale(1);} }
    .modal-content h3 { margin-top: 0; font-size: 1.5rem; color: #333; }
    .modal-content p { margin-bottom: 1.5rem; color: #5a5c69; }
    .modal-buttons { display: flex; gap: 1rem; justify-content: center; }
    .modal-buttons .btn { flex-grow: 1; max-width: 120px; }

    .student-count-display {
        margin-left: auto; /* Pushes this to the far right */
        text-align: right;
    }
    .student-count-display strong {
        font-size: 1.5rem;
        color: #4e73df;
    }
    .student-count-display span {
        display: block;
        font-size: 0.9rem;
        color: #858796;
        font-weight: 500;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 5px;
        border: 1px solid transparent;
        font-weight: 500;
    }
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }
</style>

<form action="{{ route('admin.students.index') }}" method="GET" class="filter-form">
    <div class="filter-group">
        <label for="search">Search by Name/RegNo/NIC</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Enter keyword...">
    </div>
    <div class="filter-group">
        <label for="faculty">Faculty</label>
        <select name="faculty" id="faculty">
            <option value="">All Faculties</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty->faculty }}" {{ request('faculty') == $faculty->faculty ? 'selected' : '' }}>
                    {{ $faculty->faculty }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label for="batch">Batch</label>
        <select name="batch" id="batch">
            <option value="">All Batches</option>
             @foreach($batches as $batch)
                <option value="{{ $batch->batch }}" {{ request('batch') == $batch->batch ? 'selected' : '' }}>
                    {{ $batch->batch }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label for="floor">Hostel Floor</label>
        <select name="floor" id="floor">
            <option value="">All Floors</option>
            <option value="0" {{ request('floor') == '0' ? 'selected' : '' }}>Ground Floor</option>
            <option value="1" {{ request('floor') == '1' ? 'selected' : '' }}>First Floor</option>
            <option value="2" {{ request('floor') == '2' ? 'selected' : '' }}>Second Floor</option>
            <option value="3" {{ request('floor') == '3' ? 'selected' : '' }}>Third Floor</option>
        </select>
    </div>
    <div class="filter-buttons">
        <button type="submit" class="btn-filter">Apply Filters</button>
        <a href="{{ route('admin.students.index') }}" class="btn-clear">Clear</a>
    </div>

    <div class="student-count-display">
        <span>Total Students</span>
        <strong>{{ $totalStudents }}</strong>
    </div>
</form>

<div class="top-controls" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Student List</h2>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Student</a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Reg No</th>
                <th>Full Name</th>
                <th>Faculty</th>
                <th>Batch</th>
                <th>Room No</th>
                <th class="actions-column">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td><strong>{{ $student->reg_no }}</strong></td>
                <td>{{ $student->full_name }}</td>
                <td>{{ $student->faculty }}</td>
                <td>{{ $student->batch }}</td>
                <td>{{ $student->room->room_number ?? 'Not Assigned' }}</td>
                <!-- <td class="actions actions-column">
                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-info"><i class="fas fa-eye"></i> Details</a>
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning"><i class="fas fa-eye"></i> Details</a>
                    <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-btn" data-form-id="{{ $student->id }}"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                </td> -->
                <td class="actions actions-column">
                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i>
                        <span class="btn-text">Details</span>
                    </a>
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        <span class="btn-text">Edit</span>
                    </a>
                    <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-btn" data-form-id="{{ $student->id }}">
                            <i class="fas fa-trash-alt"></i>
                            <span class="btn-text">Delete</span>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem;">No students found matching your criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $students->appends(request()->query())->links() }}
</div>


<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Do you need to delete this from the system?</p>
        <div class="modal-buttons">
            <button id="cancel-delete" class="btn btn-secondary">Cancel</button>
            <button id="confirm-delete" class="btn btn-danger">Yes, Delete</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 5px; border: 1px solid transparent; }
    .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteModal');
    const cancelBtn = document.getElementById('cancel-delete');
    const confirmBtn = document.getElementById('confirm-delete');
    const deleteBtns = document.querySelectorAll('.delete-btn');
    let formToSubmit = null;

    deleteBtns.forEach(button => {
        button.addEventListener('click', function () {
            const formId = this.dataset.formId;
            formToSubmit = document.getElementById(`delete-form-${formId}`);
            modal.style.display = 'block';
        });
    });

    cancelBtn.onclick = function() {
        modal.style.display = 'none';
        formToSubmit = null;
    }

    confirmBtn.onclick = function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            formToSubmit = null;
        }
    }
});
</script>
@endpush