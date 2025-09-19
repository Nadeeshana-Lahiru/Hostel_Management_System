{{-- resources/views/student/settings/profile.blade.php --}}
@extends('student.layout')
@section('title', 'Update Profile')
@section('page-title', 'Update Your Profile')

@section('content')
<style>
    .form-container { max-width: 900px; margin: auto; background-color: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
    .form-group { display: flex; flex-direction: column; }
    .full-width { grid-column: 1 / -1; }
    fieldset { border: 1px solid #e3e6f0; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; background-color: #f8f9fc; }
    legend { font-weight: 600; font-size: 1.1rem; color: #4e73df; padding: 0 10px; }
    label { font-weight: 500; margin-bottom: 0.5rem; color: #5a5c69; }
    input[type="text"], input[type="email"], input[type="date"], select { width: 100%; padding: 0.75rem; border-radius: 5px; border: 1px solid #d1d3e2; box-sizing: border-box; }
    .form-buttons { display: flex; gap: 1rem; margin-top: 1.5rem; justify-content: center; }
    .btn { flex-grow: 1; max-width: 200px; padding: 0.85rem; font-size: 1rem; font-weight: 600; border-radius: 5px; cursor: pointer; text-align: center; text-decoration: none; border: none; }
    .btn-submit { background-color: #4e73df; color: white; }
    .btn-secondary { background-color: #858796; color: white; }
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border-radius: 8px; width: 90%; max-width: 450px; }
    .modal-header h3 { margin-top:0; }
    .modal-buttons { display: flex; gap: 1rem; margin-top: 1.5rem; }
</style>

<div class="form-container">
    @if ($errors->any())
        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form id="updateProfileForm" action="{{ route('student.settings.updateProfile') }}" method="POST">
        @csrf
        <fieldset>
            <legend>Personal & Contact Information</legend>
            <div class="form-grid">
                <div class="form-group"><label>Name with Initials</label><input type="text" name="initial_name" value="{{ old('initial_name', $student->initial_name ?? '') }}" required></div>
                <div class="form-group"><label>Full Name</label><input type="text" name="full_name" value="{{ old('full_name', $student->full_name ?? '') }}" required></div>
                <div class="form-group"><label>NIC</label><input type="text" name="nic" value="{{ old('nic', $student->nic ?? '') }}" required></div>
                <div class="form-group"><label>Date of Birth</label><input type="date" name="dob" value="{{ old('dob', $student->dob ?? '') }}" required></div>
                <div class="form-group"><label>Gender</label><select name="gender" required><option value="male" {{ old('gender', $student->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option></select></div>
                <div class="form-group"><label>Email Address</label><input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required></div>
                <div class="form-group"><label>Telephone</label><input type="text" name="telephone_number" value="{{ old('telephone_number', $student->telephone_number ?? '') }}" required></div>
                <div class="form-group"><label>Address</label><input type="text" name="address" value="{{ old('address', $student->address ?? '') }}" required></div>
                <div class="form-group"><label>Province</label><select name="province" id="province-select" required></select></div>
                <div class="form-group"><label>District</label><select name="district" id="district-select" required></select></div>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Guardian Information</legend>
            <div class="form-grid">
                <div class="form-group"><label>Guardian Name</label><input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name ?? '') }}" required></div>
                <div class="form-group"><label>Guardian Relationship</label><input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship ?? '') }}" required></div>
                <div class="form-group"><label>Guardian Mobile</label><input type="text" name="guardian_mobile" value="{{ old('guardian_mobile', $student->guardian_mobile ?? '') }}" required></div>
            </div>
        </fieldset>

        <div class="form-buttons">
            <button type="button" id="updateProfileBtn" class="btn btn-submit">Update Profile</button>
            <a href="{{ route('student.settings.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<div id="confirmModal" class="modal"><div class="modal-content"><h3>Confirm Update</h3><p>Are you sure you want to save these changes?</p><div class="modal-buttons"><button type="button" class="btn btn-secondary" id="cancelUpdate">Cancel</button><button type="button" class="btn btn-primary" id="confirmUpdate">Confirm</button></div></div></div>
<div id="successModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Success!</h3></div><p style="text-align: center;">Your profile has been updated.</p><div class="modal-buttons" style="justify-content: center;"><button type="button" class="btn btn-primary" id="successOkBtn" style="flex-grow: 0.5;">OK</button></div></div></div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationData={"Western":["Colombo","Gampaha","Kalutara"],"Central":["Kandy","Matale","Nuwara Eliya"],"Southern":["Galle","Matara","Hambantota"],"Northern":["Jaffna","Kilinochchi","Mannar","Mullaitivu","Vavuniya"],"Eastern":["Trincomalee","Batticaloa","Ampara"],"North Western":["Kurunegala","Puttalam"],"North Central":["Anuradhapura","Polonnaruwa"],"Uva":["Badulla","Monaragala"],"Sabaragamuwa":["Ratnapura","Kegalle"]};
    const provinceSelect=document.getElementById('province-select'),districtSelect=document.getElementById('district-select');
    const savedProvince="{{ old('province', $student->province ?? '') }}",savedDistrict="{{ old('district', $student->district ?? '') }}";
    provinceSelect.innerHTML='<option value="">Select Province...</option>';
    for(const province in locationData){const option=document.createElement('option');option.value=province;option.textContent=province;if(province===savedProvince)option.selected=true;provinceSelect.appendChild(option)}
    function updateDistricts(){const selectedProvince=provinceSelect.value;districtSelect.innerHTML='<option value="">Select District...</option>';if(selectedProvince&&locationData[selectedProvince]){districtSelect.disabled=false;locationData[selectedProvince].forEach(function(district){const option=document.createElement('option');option.value=district;option.textContent=district;if(district===savedDistrict)option.selected=true;districtSelect.appendChild(option)})}else{districtSelect.disabled=true}}
    updateDistricts();provinceSelect.addEventListener('change',updateDistricts);
    const confirmModal=document.getElementById('confirmModal'),successModal=document.getElementById('successModal'),updateProfileBtn=document.getElementById('updateProfileBtn'),mainForm=document.getElementById('updateProfileForm'),cancelUpdateBtn=document.getElementById('cancelUpdate'),confirmUpdateBtn=document.getElementById('confirmUpdate'),successOkBtn=document.getElementById('successOkBtn');
    updateProfileBtn.addEventListener('click',function(e){e.preventDefault();confirmModal.style.display='block'});
    cancelUpdateBtn.onclick=function(){confirmModal.style.display='none'};
    successOkBtn.onclick=function(){window.location.href="{{ route('student.settings.index') }}"};
    confirmUpdateBtn.onclick=function(){const formData=new FormData(mainForm),csrfToken=document.querySelector('input[name="_token"]').value;
    fetch(mainForm.action,{method:'POST',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:formData}).then(response=>response.json()).then(data=>{if(data.success){confirmModal.style.display='none';successModal.style.display='block'}else{alert('An error occurred.');console.error('Error:',data.errors||'Unknown error')}}).catch(error=>{console.error('Fetch Error:',error);alert('A network error occurred.')})};
});
</script>
@endpush