{{-- resources/views/warden/settings/profile.blade.php --}}
@extends('warden.layout')
@section('title', 'Update Profile')
@section('page-title', 'Update Your Profile')

@section('content')
<style>
    .form-container { max-width: 900px; margin: auto; background-color: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
    .form-group { display: flex; flex-direction: column; }
    .full-width { grid-column: 1 / -1; }
    .two-thirds-width { grid-column: span 2; }
    fieldset { border: 1px solid #e3e6f0; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; background-color: #f8f9fc; }
    legend { font-weight: 600; font-size: 1.1rem; color: #4e73df; padding: 0 10px; }
    label { font-weight: 500; margin-bottom: 0.5rem; color: #5a5c69; }
    input[type="text"], input[type="email"], input[type="date"], select, textarea { width: 100%; padding: 0.75rem; border-radius: 5px; border: 1px solid #d1d3e2; box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s; }
    input:focus, select:focus, textarea:focus { outline: none; border-color: #4e73df; box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25); }
    .form-buttons { display: flex; gap: 1rem; margin-top: 1.5rem; justify-content: center; }
    .btn { flex-grow: 1; max-width: 200px; padding: 0.85rem; font-size: 1rem; font-weight: 600; border-radius: 5px; cursor: pointer; text-align: center; text-decoration: none; border: none; transition: all 0.2s; }
    .btn-submit { background-color: #4e73df; color: white; }
    .btn-submit:hover { background-color: #2e59d9; }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-secondary:hover { background-color: #717384; }
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-buttons { display: flex; gap: 1rem; margin-top: 1.5rem; }
</style>

<div class="form-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="updateProfileForm" action="{{ route('warden.settings.updateProfile') }}" method="POST">
        @csrf
        <fieldset>
            <legend>Personal Information</legend>
            <div class="form-grid">
                <div class="form-group two-thirds-width">
                    <label>Name with Initials</label>
                    <input type="text" name="initial_name" value="{{ old('initial_name', $warden->initial_name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select...</option>
                        <option value="male" {{ old('gender', $warden->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $warden->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $warden->full_name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>NIC</label>
                    <input type="text" name="nic" value="{{ old('nic', $warden->nic ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', $warden->dob ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                </div>
                <div class="form-group full-width">
                    <label>Address</label>
                    <input type="text" name="address" value="{{ old('address', $warden->address ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Telephone</label>
                    <input type="text" name="telephone_number" value="{{ old('telephone_number', $warden->telephone_number ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $warden->nationality ?? 'Sri Lankan') }}" required>
                </div>
                <div class="form-group">
                    <label>Civil Status</label>
                    <select name="civil_status" required>
                        <option value="unmarried" {{ old('civil_status', $warden->civil_status ?? '') == 'unmarried' ? 'selected' : '' }}>Unmarried</option>
                        <option value="married" {{ old('civil_status', $warden->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
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
            <button type="button" id="updateProfileBtn" class="btn btn-submit">Update Profile</button>
            <a href="{{ route('warden.settings.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h3>Confirm the Update</h3>
        <p>Are you sure you want to save these changes?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary" id="cancelUpdate">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirmUpdate">Confirm</button>
        </div>
    </div>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h3>Success!</h3></div>
        <p style="text-align: center;">Your profile has been updated.</p>
        <div class="modal-buttons" style="justify-content: center;">
            <button type="button" class="btn btn-primary" id="successOkBtn" style="flex-grow: 0.5;">OK</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationData={"Western":["Colombo","Gampaha","Kalutara"],"Central":["Kandy","Matale","Nuwara Eliya"],"Southern":["Galle","Matara","Hambantota"],"Northern":["Jaffna","Kilinochchi","Mannar","Mullaitivu","Vavuniya"],"Eastern":["Trincomalee","Batticaloa","Ampara"],"North Western":["Kurunegala","Puttalam"],"North Central":["Anuradhapura","Polonnaruwa"],"Uva":["Badulla","Monaragala"],"Sabaragamuwa":["Ratnapura","Kegalle"]};
    const provinceSelect=document.getElementById('province-select'),districtSelect=document.getElementById('district-select');
    const savedProvince="{{ old('province', $warden->province ?? '') }}",savedDistrict="{{ old('district', $warden->district ?? '') }}";
    provinceSelect.innerHTML='<option value="">Select Province...</option>';
    for(const province in locationData){const option=document.createElement('option');option.value=province;option.textContent=province;if(province===savedProvince)option.selected=true;provinceSelect.appendChild(option)}
    function updateDistricts(){const selectedProvince=provinceSelect.value;districtSelect.innerHTML='<option value="">Select District...</option>';if(selectedProvince&&locationData[selectedProvince]){districtSelect.disabled=false;locationData[selectedProvince].forEach(function(district){const option=document.createElement('option');option.value=district;option.textContent=district;if(district===savedDistrict)option.selected=true;districtSelect.appendChild(option)})}else{districtSelect.disabled=true}}
    updateDistricts();provinceSelect.addEventListener('change',updateDistricts);
    const confirmModal=document.getElementById('confirmModal'),successModal=document.getElementById('successModal'),updateProfileBtn=document.getElementById('updateProfileBtn'),mainForm=document.getElementById('updateProfileForm'),cancelUpdateBtn=document.getElementById('cancelUpdate'),confirmUpdateBtn=document.getElementById('confirmUpdate'),successOkBtn=document.getElementById('successOkBtn');
    updateProfileBtn.addEventListener('click',function(e){e.preventDefault();confirmModal.style.display='block'});
    cancelUpdateBtn.onclick=function(){confirmModal.style.display='none'};
    successOkBtn.onclick=function(){window.location.href="{{ route('warden.settings.index') }}"};
    confirmUpdateBtn.onclick=function(){const formData=new FormData(mainForm),csrfToken=document.querySelector('input[name="_token"]').value;
    fetch(mainForm.action,{method:'POST',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:formData}).then(response=>response.json()).then(data=>{if(data.success){confirmModal.style.display='none';successModal.style.display='block'}else{alert('An error occurred.');console.error('Error:',data.errors||'Unknown error')}}).catch(error=>{console.error('Fetch Error:',error);alert('A network error occurred.')})};
});
</script>
@endpush