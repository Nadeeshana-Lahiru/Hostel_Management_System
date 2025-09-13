@extends('admin.layout')

@section('title', 'Add Warden')
@section('page-title', 'Add New Warden')

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
    
    /* Button Styles */
    .form-buttons {
        display: flex;
        gap: 10px; /* Adds space between buttons */
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
    .btn-submit { background-color: #007bff; color: white; }
    .btn-submit:hover { background-color: #0056b3; }
    
    /* New Style for Cancel Button */
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

    <form action="{{ route('admin.wardens.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label for="initial_name">Initial Name</label>
                <input type="text" id="initial_name" name="initial_name" value="{{ old('initial_name') }}" required>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
            </div>
            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" id="nic" name="nic" value="{{ old('nic') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
             <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="{{ old('dob') }}" required>
            </div>
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}" value="Sri Lankan" required>
            </div>
             <div class="form-group">
                <label for="civil_status">Civil Status</label>
                <select id="civil_status" name="civil_status" required>
                    <option value="">Select Status</option>
                    <option value="unmarried" {{ old('civil_status') == 'unmarried' ? 'selected' : '' }}>Unmarried</option>
                    <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                </select>
            </div>
            <div class="form-group">
                <label for="district">District</label>
                <input type="text" id="district" name="district" value="{{ old('district') }}" required>
            </div>
            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" value="{{ old('province') }}" required>
            </div>
            <div class="form-group">
                <label for="telephone_number">Telephone Number</label>
                <input type="text" id="telephone_number" name="telephone_number" value="{{ old('telephone_number') }}" required>
            </div>
            <div class="form-group full-width">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
            </div>
        </div>
        <div class="form-buttons">
            <button type="submit" class="btn btn-submit">Add Warden</button>
            <a href="{{ route('admin.wardens.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection