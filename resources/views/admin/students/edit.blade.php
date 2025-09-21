@extends('admin.layout')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student Details')

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
    input[readonly] {
        background-color: #eaecf4;
        cursor: not-allowed;
    }
    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        width: 50%;
        margin-left: 25%;
    }
    .btn {
        flex-grow: 1;
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
    .btn-submit { background-color: #4e73df; color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);} 
    .btn-submit:hover { background-color: #2e59d9; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(78, 115, 223, 0.4);  }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-secondary:hover { background-color: #717384; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(78, 115, 223, 0.4); }

    .page-actions {
        margin-bottom: 25px;
        margin-left: 70%;
    }
    .btn-back {
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
    .btn-back:hover {
        background-color: #717384;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
</style>

<div class="page-actions">
    <a href="{{ route('admin.students.index') }}" class="btn-back">&larr; Go Back to Student List</a>
</div>

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

    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Personal Information -->
        <fieldset>
            <legend>Personal Information</legend>
            <div class="form-grid">
                <div class="form-group two-thirds-width"><label for="initial_name">Initial Name</label><input type="text" name="initial_name" value="{{ old('initial_name', $student->initial_name) }}" required></div>
                <div class="form-group"><label for="gender">Gender</label><select name="gender" required><option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option></select></div>
                <div class="form-group full-width"><label for="full_name">Full Name</label><input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" required></div>
                <div class="form-group"><label for="nic">NIC (Cannot change)</label><input type="text" value="{{ $student->nic }}" readonly></div>
                <div class="form-group"><label for="dob">Date of Birth</label><input type="date" name="dob" value="{{ old('dob', $student->dob) }}" required></div>
                <div class="form-group"><label for="email">Email</label><input type="email" name="email" value="{{ old('email', $student->user->email) }}" required></div>
                <div class="form-group full-width"><label for="address">Address</label><input type="text" name="address" value="{{ old('address', $student->address) }}" required></div>
                <div class="form-group"><label for="telephone_number">Telephone</label><input type="text" name="telephone_number" value="{{ old('telephone_number', $student->telephone_number) }}" required></div>
                <div class="form-group"><label for="nationality">Nationality</label><input type="text" name="nationality" value="{{ old('nationality', $student->nationality) }}"></div>
                <div class="form-group">
                    <label for="religion">Religion</label>
                    <select name="religion" id="religion">
                        <option value="">Select...</option>
                        <option value="Buddhism" {{ old('religion', $student->religion) == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                        <option value="Hinduism" {{ old('religion', $student->religion) == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                        <option value="Islam" {{ old('religion', $student->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Christianity" {{ old('religion', $student->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                        <option value="Other" {{ old('religion', $student->religion) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group"><label for="civil_status">Civil Status</label><select name="civil_status"><option value="unmarried" {{ old('civil_status', $student->civil_status) == 'unmarried' ? 'selected' : '' }}>Unmarried</option><option value="married" {{ old('civil_status', $student->civil_status) == 'married' ? 'selected' : '' }}>Married</option></select></div>
                <div class="form-group">
                    <label for="province">Province</label>
                    <select name="province" id="province-select">
                        <option value="">Select Province...</option>
                        {{-- Options will be added by JavaScript --}}
                    </select>
                </div>
                <div class="form-group">
                    <label for="district">District</label>
                    <select name="district" id="district-select">
                        <option value="">Select Province First</option>
                    </select>
                </div>
                <div class="form-group"><label for="gn_division">GN Division</label><input type="text" name="gn_division" value="{{ old('gn_division', $student->gn_division) }}"></div>
            </div>
        </fieldset>

        <!-- Educational Information -->
        <fieldset>
            <legend>Educational Information</legend>
            <div class="form-grid">
                <div class="form-group"><label for="reg_no">Reg No (Cannot change)</label><input type="text" value="{{ $student->reg_no }}" readonly></div>
                <div class="form-group"><label for="batch">Batch</label><input type="text" name="batch" value="{{ old('batch', $student->batch) }}" required></div>
                <div class="form-group"><label for="year">Year</label><select name="year" required><option value="1" {{ old('year', $student->year) == 1 ? 'selected' : '' }}>1st Year</option><option value="2" {{ old('year', $student->year) == 2 ? 'selected' : '' }}>2nd Year</option><option value="3" {{ old('year', $student->year) == 3 ? 'selected' : '' }}>3rd Year</option><option value="4" {{ old('year', $student->year) == 4 ? 'selected' : '' }}>4th Year</option></select></div>
                <div class="form-group"><label for="faculty">Faculty</label><input type="text" name="faculty" value="{{ old('faculty', $student->faculty) }}" required></div>
                <div class="form-group"><label for="department">Department</label><input type="text" name="department" value="{{ old('department', $student->department) }}" required></div>
                <div class="form-group"><label for="course">Course</label><input type="text" name="course" value="{{ old('course', $student->course) }}"></div>
            </div>
        </fieldset>

        <!-- Parent / Guardian Information -->
        <fieldset>
            <legend>Parent / Guardian Information</legend>
            <div class="form-grid">
                <div class="form-group two-thirds-width"><label for="guardian_name">Name</label><input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" required></div>
                <div class="form-group"><label for="guardian_relationship">Relationship</label><select name="guardian_relationship" required><option value="mother" {{ old('guardian_relationship', $student->guardian_relationship) == 'mother' ? 'selected' : '' }}>Mother</option><option value="father" {{ old('guardian_relationship', $student->guardian_relationship) == 'father' ? 'selected' : '' }}>Father</option><option value="guardian" {{ old('guardian_relationship', $student->guardian_relationship) == 'guardian' ? 'selected' : '' }}>Guardian</option></select></div>
                <div class="form-group"><label for="guardian_dob">Date of Birth</label><input type="date" name="guardian_dob" value="{{ old('guardian_dob', $student->guardian_dob) }}"></div>
                <div class="form-group"><label for="guardian_mobile">Mobile</label><input type="text" name="guardian_mobile" value="{{ old('guardian_mobile', $student->guardian_mobile) }}" required></div>
                <div class="form-group full-width"></div>
                <div class="form-group two-thirds-width"><label for="emergency_contact_name">Emergency Contact Name</label><input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}" required></div>
                <div class="form-group"><label for="emergency_contact_number">Emergency Contact Number</label><input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number', $student->emergency_contact_number) }}" required></div>
            </div>
        </fieldset>

        <!-- Medical Information -->
        <fieldset>
            <legend>Medical Information</legend>
            <div class="form-group full-width"><label for="medical_info">Long-term Medical Treatments (if any)</label><textarea name="medical_info" rows="3">{{ old('medical_info', $student->medical_info) }}</textarea></div>
        </fieldset>
        
        <div class="form-buttons">
            <button type="submit" class="btn btn-submit">Update Details</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationData = {
        "Western": ["Colombo", "Gampaha", "Kalutara"],
        "Central": ["Kandy", "Matale", "Nuwara Eliya"],
        "Southern": ["Galle", "Matara", "Hambantota"],
        "Northern": ["Jaffna", "Kilinochchi", "Mannar", "Mullaitivu", "Vavuniya"],
        "Eastern": ["Trincomalee", "Batticaloa", "Ampara"],
        "North Western": ["Kurunegala", "Puttalam"],
        "North Central": ["Anuradhapura", "Polonnaruwa"],
        "Uva": ["Badulla", "Monaragala"],
        "Sabaragamuwa": ["Ratnapura", "Kegalle"]
    };

    const provinceSelect = document.getElementById('province-select');
    const districtSelect = document.getElementById('district-select');

    // Get the student's saved data from Blade
    const savedProvince = "{{ old('province', $student->province) }}";
    const savedDistrict = "{{ old('district', $student->district) }}";

    // Populate the Province dropdown
    for (const province in locationData) {
        const option = document.createElement('option');
        option.value = province;
        option.textContent = province;
        // If this province matches the student's saved province, select it
        if (province === savedProvince) {
            option.selected = true;
        }
        provinceSelect.appendChild(option);
    }

    // Function to update districts based on province
    function updateDistricts() {
        const selectedProvince = provinceSelect.value;
        districtSelect.innerHTML = '<option value="">Select District...</option>';
        
        if (selectedProvince && locationData[selectedProvince]) {
            districtSelect.disabled = false;
            locationData[selectedProvince].forEach(function (district) {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                // If this district matches the student's saved district, select it
                if (district === savedDistrict) {
                    option.selected = true;
                }
                districtSelect.appendChild(option);
            });
        } else {
            districtSelect.disabled = true;
            districtSelect.innerHTML = '<option value="">Select Province First</option>';
        }
    }

    // Run the function once on page load to set the initial state
    updateDistricts();

    // Add event listener for any future changes
    provinceSelect.addEventListener('change', updateDistricts);
});
</script>
@endpush