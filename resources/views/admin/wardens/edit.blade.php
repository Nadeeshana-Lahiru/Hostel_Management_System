@extends('admin.layout')

@section('title', 'Edit Warden')
@section('page-title', 'Edit Warden Details')

@section('content')
<style>
    .form-container { max-width: 800px; margin: auto; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; }
    label { font-weight: 600; margin-bottom: 5px; color: #555; }
    input[type="text"], input[type="email"], input[type="date"], input[type="password"], select, textarea {
        width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd; box-sizing: border-box;
    }
    .form-group.full-width { grid-column: 1 / -1; }
    
    .form-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        margin-left: 30%;
    }
    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
    }
    .btn-submit { background-color: #4e73df; color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);} 
    .btn-submit:hover { background-color: #2e59d9; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(78, 115, 223, 0.4);  }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-secondary:hover { background-color: #717384; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(78, 115, 223, 0.4); }
    .alert-danger { list-style-type: none; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px; margin-bottom: 20px; }
</style>

<div class="form-container">
    @if ($errors->any())
        <div style="list-style-type: none; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
            <strong>Whoops! Something went wrong.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editWardenForm" action="{{ route('admin.wardens.update', $warden->id) }}" method="POST">
        @csrf
        @method('PUT') 
        
        <div class="form-grid">
            <div class="form-group">
                <label for="initial_name">Initial Name</label>
                <input type="text" id="initial_name" name="initial_name" value="{{ old('initial_name', $warden->initial_name) }}" required>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $warden->full_name) }}" required>
            </div>
            <div class="form-group">
                <label for="nic">NIC (Cannot be changed)</label>
                <input type="text" id="nic" name="nic" value="{{ $warden->nic }}" readonly style="background-color: #e9ecef;">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $warden->user->email) }}" required>
            </div>
             <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="male" {{ $warden->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $warden->gender == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="{{ old('dob', $warden->dob) }}" required>
            </div>
            <div class="form-group">
                <label for="telephone_number">Teliphone</label>
                <input type="text" id="telephone_number" name="telephone_number" value="{{ old('telephone_number', $warden->telephone_number) }}" required>
            </div>
            <div class="form-group full-width">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3" required>{{ old('address', $warden->address) }}</textarea>
            </div>
        </div>
        <div class="form-buttons">
            <button type="button" id="updateBtn" class="btn btn-submit">Update Warden</button>
            <a href="{{ route('admin.wardens.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<div id="confirmModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center;">
        <h4 style="margin-top: 0;">Confirm Update</h4>
        <p>Are you sure you want to make these changes?</p>
        <div class="modal-buttons" style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
            <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
            <button type="button" id="confirmBtn" class="btn btn-submit">Yes, Update</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('editWardenForm');
    const updateBtn = document.getElementById('updateBtn');
    const confirmModal = document.getElementById('confirmModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');

    updateBtn.addEventListener('click', function () {
        confirmModal.style.display = 'block';
    });

    cancelBtn.addEventListener('click', function () {
        confirmModal.style.display = 'none';
    });

    confirmBtn.addEventListener('click', function () {
        editForm.submit();
    });
});
</script>
@endpush