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
    .btn-submit { background-color: #1cc88a; color: white; } /* Green for update */
    .btn-submit:hover { background-color: #17a673; }
    
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-secondary:hover { background-color: #5a6268; }
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

    <form action="{{ route('admin.wardens.update', $warden->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Important for updates --}}
        
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
            {{-- ... Fill the value for all other inputs similarly ... --}}
            <div class="form-group full-width">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3" required>{{ old('address', $warden->address) }}</textarea>
            </div>
        </div>
        <div class="form-buttons">
            <button type="submit" class="btn btn-submit">Update Warden</button>
            <a href="{{ route('admin.wardens.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection