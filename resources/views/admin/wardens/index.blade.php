@extends('admin.layout')

@section('title', 'All Wardens')
@section('page-title', 'Manage Wardens')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .actions a, .actions button { margin-right: 5px; }
    .btn { padding: 10px 10px; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; }
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
    .top-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }

    .actions .btn {
        padding: 0.5rem 0.6rem;
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
        transition: all 0.3s ease; 
    }

    .btn-text {
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        white-space: nowrap;
        padding-left: 0;
    }

    .actions .btn:hover .btn-text {
        max-width: 100px; 
        opacity: 1;
        padding-left: 0.4rem;
    }

    .actions-column {
        text-align: right;
    }

    .btn-primary {
        background-color: #4e73df;
        padding: 10px 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-primary:hover {
        background-color: #2e59d9; 
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.4);
    }
    .btn-secondary { background-color: #858796; color: white; text-decoration: none; }
    .btn-info { background-color: #36b9cc; } 
    .btn-info:hover { background-color: #2a96a5; }
    .btn-warning { background-color: #f6c23e; }
    .btn-warning:hover { background-color: #dda20a; }
    .btn-danger { background-color: #e74a3b; }
    .btn-danger:hover { background-color: #be2617; }

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

    .search-and-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f9fc;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .search-form input {
        width: 300px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .search-form .btn {
        padding: 10px 20px;
        font-weight: 600;
        white-space: nowrap;
    }

    .warden-count-display {
        text-align: right;
    }
    .warden-count-display strong {
        font-size: 1.5rem;
        color: #4e73df;
    }
    .warden-count-display span {
        display: block;
        font-size: 0.9rem;
        color: #858796;
        font-weight: 500;
    }
</style>

<div class="search-and-controls">
    <form action="{{ route('admin.wardens.index') }}" method="GET" class="search-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Warden Name or NIC...">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('admin.wardens.index') }}" class="btn btn-secondary">Clear</a>
    </form>

    <div class="warden-count-display">
        <span>Total Wardens</span>
        <strong>{{ $totalWardens }}</strong>
    </div>
</div>

<div class="top-controls">
    <h2>All Wardens</h2>
    <a href="{{ route('admin.wardens.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Warden</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>NIC</th>
                <th>Email</th>
                <th>Telephone</th>
                <th class="actions-column">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wardens as $warden)
            <tr>
                <td>{{ $warden->full_name }}</td>
                <td>{{ $warden->nic }}</td>
                <td>{{ $warden->user->email ?? 'N/A' }}</td>
                <td>{{ $warden->telephone_number }}</td>
                
                <!-- <td class="actions actions-column">
                    <a href="{{ route('admin.wardens.show', $warden->id) }}" class="btn btn-info"><i class="fas fa-eye"></i> Details</a>
                    <a href="{{ route('admin.wardens.edit', $warden->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                    <form id="delete-form-{{ $warden->id }}" action="{{ route('admin.wardens.destroy', $warden->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-btn" data-form-id="{{ $warden->id }}"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                </td> -->
                <td class="actions actions-column">
                    <a href="{{ route('admin.wardens.show', $warden->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i>
                        <span class="btn-text">Details</span>
                    </a>
                    <a href="{{ route('admin.wardens.edit', $warden->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        <span class="btn-text">Edit</span>
                    </a>
                    <form id="delete-form-{{ $warden->id }}" action="{{ route('admin.wardens.destroy', $warden->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-btn" data-form-id="{{ $warden->id }}">
                            <i class="fas fa-trash-alt"></i>
                            <span class="btn-text">Delete</span>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No wardens found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
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