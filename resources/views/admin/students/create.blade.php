@extends('admin.layout')

@section('title', 'Add Student')
@section('page-title', 'Add New Student')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: auto;
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .full-width { grid-column: 1 / -1; }
    .two-thirds-width { grid-column: span 2; }

    fieldset {
        border: 1px solid #e3e6f0;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        background-color: #f8f9fc;
    }
    legend {
        font-weight: 600;
        font-size: 1.1rem;
        color: #4e73df;
        padding: 0 10px;
        background-color: #f8f9fc;
        position: relative;
        top: -2.5rem;
        left: 1rem;
        width: auto;
    }
    label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #5a5c69;
    }
    input[type="text"], input[type="email"], input[type="date"], select, textarea {
        width: 100%;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #d1d3e2;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
    }
    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        width: 50%;
        margin-left: 25%;
    }
    .btn {
        flex-grow: 1; /* Makes buttons share space equally */
        padding: 0.85rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border: none;
        transition: all 0.2s;
    }
    .btn-submit { background-color: #4e73df; color: white; }
    .btn-submit:hover { background-color: #2e59d9; }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-secondary:hover { background-color: #717384; }
</style>

<div class="form-container">
    @if ($errors->any())
        <div class="alert-danger" style="list-style-type: none; padding: 1rem; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px; margin-bottom: 1.5rem;">
            <strong>Whoops! Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.students.store') }}" method="POST">
        @csrf
        
        <fieldset>
            <legend>Personal Information</legend>
            <div class="form-grid">
                <div class="form-group two-thirds-width"><label for="initial_name">Initial Name</label><input type="text" name="initial_name" value="{{ old('initial_name') }}" required></div>
                <div class="form-group"><label for="gender">Gender</label><select name="gender" required><option value="">Select...</option><option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option></select></div>
                <div class="form-group full-width"><label for="full_name">Full Name</label><input type="text" name="full_name" value="{{ old('full_name') }}" required></div>
                <div class="form-group"><label for="nic">NIC</label><input type="text" name="nic" value="{{ old('nic') }}" required></div>
                <div class="form-group"><label for="dob">Date of Birth</label><input type="date" name="dob" value="{{ old('dob') }}" required></div>
                <div class="form-group"><label for="email">Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                <div class="form-group full-width"><label for="address">Address</label><input type="text" name="address" value="{{ old('address') }}" required></div>
                <div class="form-group"><label for="telephone_number">Telephone</label><input type="text" name="telephone_number" value="{{ old('telephone_number') }}" required></div>
                <div class="form-group"><label for="nationality">Nationality</label><input type="text" name="nationality" value="{{ old('nationality', 'Sri Lankan') }}"></div>
                <div class="form-group"><label for="religion">Religion</label><input type="text" name="religion" value="{{ old('religion') }}"></div>
                <div class="form-group"><label for="civil_status">Civil Status</label><select name="civil_status"><option value="unmarried">Unmarried</option><option value="married">Married</option></select></div>
                <div class="form-group"><label for="district">District</label><input type="text" name="district" value="{{ old('district') }}"></div>
                <div class="form-group"><label for="province">Province</label><input type="text" name="province" value="{{ old('province') }}"></div>
                <div class="form-group"><label for="gn_division">GN Division</label><input type="text" name="gn_division" value="{{ old('gn_division') }}"></div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Educational Information</legend>
            <div class="form-grid">
                <div class="form-group"><label for="reg_no">Reg No</label><input type="text" name="reg_no" value="{{ old('reg_no') }}" required></div>
                <div class="form-group"><label for="batch">Batch</label><input type="text" name="batch" value="{{ old('batch') }}" required></div>
                <div class="form-group"><label for="year">Year</label><select name="year" required><option value="">Select...</option><option value="1">1st Year</option><option value="2">2nd Year</option><option value="3">3rd Year</option><option value="4">4th Year</option></select></div>
                <div class="form-group"><label for="faculty">Faculty</label><input type="text" name="faculty" value="{{ old('faculty') }}" required></div>
                <div class="form-group"><label for="department">Department</label><input type="text" name="department" value="{{ old('department') }}" required></div>
                <div class="form-group"><label for="course">Course</label><input type="text" name="course" value="{{ old('course') }}"></div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Parent / Guardian Information</legend>
            <div class="form-grid">
                <div class="form-group two-thirds-width"><label for="guardian_name">Name</label><input type="text" name="guardian_name" value="{{ old('guardian_name') }}" required></div>
                <div class="form-group"><label for="guardian_relationship">Relationship</label><select name="guardian_relationship" required><option value="">Select...</option><option value="mother">Mother</option><option value="father">Father</option><option value="guardian">Guardian</option></select></div>
                <div class="form-group"><label for="guardian_dob">Date of Birth</label><input type="date" name="guardian_dob" value="{{ old('guardian_dob') }}"></div>
                <div class="form-group"><label for="guardian_mobile">Mobile</label><input type="text" name="guardian_mobile" value="{{ old('guardian_mobile') }}" required></div>
                <div class="form-group full-width"></div>
                <div class="form-group two-thirds-width"><label for="emergency_contact_name">Emergency Contact Name</label><input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required></div>
                <div class="form-group"><label for="emergency_contact_number">Emergency Contact Number</label><input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}" required></div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Medical Information</legend>
            <div class="form-group full-width"><label for="medical_info">Long-term Medical Treatments (if any)</label><textarea name="medical_info" rows="3">{{ old('medical_info') }}</textarea></div>
        </fieldset>
        
        <div class="form-buttons">
            <button type="submit" class="btn btn-submit">Add Student</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection