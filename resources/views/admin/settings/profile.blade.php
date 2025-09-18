@extends('admin.layout')
@section('title', 'Update Profile')
@section('page-title', 'Update Your Profile')

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

    /* --- BEAUTIFUL MODAL STYLES --- */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; color: #333; }
    .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-step { display: none; }
    .modal-step.active { display: block; }
    
    /* Modal Form Element Styles */
    .modal .form-group { margin-bottom: 1rem; }
    .modal label { text-align: left; display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
    .modal input[type="text"], .modal input[type="password"], .modal input[type="email"] {
        width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;
        box-sizing: border-box; transition: all 0.2s;
    }
    .modal input:focus { outline: none; border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2); }

    /* Modal Button Styles */
    .modal-buttons { display: flex; gap: 1rem; margin-top: 1.5rem; }
    .modal .btn { flex-grow: 1; padding: 0.75rem; font-size: 0.9rem; }
    .btn-primary { background-color: #4e73df; color: white; }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-submit { background-color: #1cc88a; color: white; }
    
    /* Modal Message/Alert Styles */
    #modal-message { padding: 10px; border-radius: 5px; margin-top: 15px; font-weight: 500; display: none; text-align: center; }
    #modal-message.success { background-color: #d1fae5; color: #065f46; }
    #modal-message.error { background-color: #fee2e2; color: #991b1b; }

    .alert-success {
        padding: 1rem; margin-bottom: 1.5rem; border-radius: 5px;
        background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; font-weight: 500;
    }
    
    /* UPDATED: Resend OTP link container */
    .resend-container {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e3e6f0;
    }

    #resend-otp {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: color 0.2s;
    }

    #resend-otp:hover {
        text-decoration: underline;
    }

    #resend-otp.disabled {
        color: #858796;
        cursor: not-allowed;
        text-decoration: none;
    }

    /* Styles to position the "eye" icon inside the input field */
    .password-group {
        position: relative;
    }
    .password-toggle {
        position: absolute;
        top: 65%; /* Vertically center relative to the label + input */
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #858796;
        user-select: none; /* Prevents text selection on double click */
    }
    /* Add padding to the password input so text doesn't go under the icon */
    .modal input[type="password"] {
        padding-right: 40px;
    }
</style>

<div class="form-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.updateProfile') }}" method="POST">
        @csrf
        <form id="updateProfileForm" action="{{ route('admin.settings.updateProfile') }}" method="POST">
        @csrf
        <fieldset>
            <legend>Personal Information</legend>
            <div class="form-grid">
                <div class="form-group two-thirds-width">
                    <label>Name with Initials</label>
                    <input type="text" name="initial_name" value="{{ old('initial_name', $admin->initial_name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select...</option>
                        <option value="male" {{ old('gender', $admin->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $admin->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $admin->full_name ?? '') }}" required>
                </div>
                
                <div class="form-group">
                    <label>NIC</label>
                    <input type="text" name="nic" value="{{ old('nic', $admin->nic ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', $admin->dob ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                </div>
                
                <div class="form-group full-width">
                    <label>Address</label>
                    <input type="text" name="address" value="{{ old('address', $admin->address ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Telephone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $admin->telephone ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $admin->nationality ?? 'Sri Lankan') }}" required>
                </div>
                <div class="form-group">
                    <label>Civil Status</label>
                    <select name="civil_status" required>
                        <option value="unmarried" {{ old('civil_status', $admin->civil_status ?? '') == 'unmarried' ? 'selected' : '' }}>Unmarried</option>
                        <option value="married" {{ old('civil_status', $admin->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Province</label>
                    <select name="province" id="province-select" required></select>
                </div>
                <div class="form-group">
                    <label>District</label>
                    <select name="district" id="district-select" required></select>
                </div>
            </div>
        </fieldset>

        <div class="form-buttons">
            <button type="submit" class="btn btn-submit">Update Profile</button>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Your Update</h3>
        <p>Are you sure you want to save these changes to your profile?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary" id="cancelUpdate">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirmUpdate">OK</button>
        </div>
    </div>
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
    const savedProvince = "{{ old('province', $admin->province ?? '') }}";
    const savedDistrict = "{{ old('district', $admin->district ?? '') }}";

    // Populate the Province dropdown
    provinceSelect.innerHTML = '<option value="">Select Province...</option>';
    for (const province in locationData) {
        const option = document.createElement('option');
        option.value = province; option.textContent = province;
        if (province === savedProvince) { option.selected = true; }
        provinceSelect.appendChild(option);
    }

    function updateDistricts() {
        const selectedProvince = provinceSelect.value;
        districtSelect.innerHTML = '<option value="">Select District...</option>';
        if (selectedProvince && locationData[selectedProvince]) {
            districtSelect.disabled = false;
            locationData[selectedProvince].forEach(function (district) {
                const option = document.createElement('option');
                option.value = district; option.textContent = district;
                if (district === savedDistrict) { option.selected = true; }
                districtSelect.appendChild(option);
            });
        } else { districtSelect.disabled = true; }
    }
    updateDistricts();
    provinceSelect.addEventListener('change', updateDistricts);

    // --- Confirmation Modal Logic ---
    const modal = document.getElementById('confirmModal');
    const updateProfileBtn = document.getElementById('updateProfileBtn');
    const mainForm = document.getElementById('updateProfileForm');
    const cancelUpdateBtn = document.getElementById('cancelUpdate');
    const confirmUpdateBtn = document.getElementById('confirmUpdate');

    updateProfileBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'block';
    });

    cancelUpdateBtn.onclick = function() { modal.style.display = 'none'; }
    confirmUpdateBtn.onclick = function() { mainForm.submit(); }
    window.onclick = function(event) { if (event.target == modal) { modal.style.display = 'none'; } }
});
</script>
@endpush