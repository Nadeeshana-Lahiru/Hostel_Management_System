@extends('student.layout')
@section('title', 'Settings')
@section('page-title', 'Account Settings')

@section('content')
<!-- === STYLES UPDATED WITH ADMIN/WARDEN MODAL STYLES === -->
<style>
    /* Main container and sidebar styles (unchanged) */
    .settings-container { display: flex; gap: 2rem; align-items: flex-start; }
    .profile-sidebar { flex: 0 0 280px; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); text-align: center; position: sticky; top: 20px; }
    .profile-picture { width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 1rem; object-fit: cover; border: 4px solid #e9f2ff; }
    .profile-sidebar h4 { margin: 0.5rem 0 0.25rem; font-size: 1.25rem; color: #333; }
    .profile-sidebar p { margin: 0; color: #888; font-size: 0.9rem; }
    .profile-sidebar-nav { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #eef2f7; }
    .profile-sidebar-nav ul { list-style: none; padding: 0; margin: 0; text-align: left; }
    .profile-sidebar-nav li a,
    .profile-sidebar-nav li button { display: flex; align-items: center; width: 100%; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; text-decoration: none; background-color: transparent; border: none; cursor: pointer; font-size: 0.95rem; font-family: inherit; font-weight: 500; transition: all 0.2s ease; }
    .profile-sidebar-nav li a { color: #0d6efd; }
    #changePasswordBtn { color: #ffb300; }
    #logoutBtn { color: #e53935; }
    .profile-sidebar-nav li a:hover { background-color: #e9f2ff; }
    #changePasswordBtn:hover { background-color: #fff9e1; }
    #logoutBtn:hover { background-color: #ffebee; }
    .profile-sidebar-nav i { margin-right: 15px; width: 20px; text-align: center; font-size: 1rem; }
    
    /* Main content styles (unchanged) */
    .settings-main-content { flex-grow: 1; }
    .settings-card { background-color: #fff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .settings-section h5 { font-size: 1.4rem; font-weight: 600; color: #333; margin-bottom: 1.5rem; display: flex; align-items: center; }
    .settings-section h5 i { margin-right: 12px; color: #0d6efd; }
    .details-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    .detail-item { background: #f8f9fc; padding: 1rem; border-radius: 8px; border: 1px solid #e3e6f0; transition: all 0.2s ease; }
    .detail-item:hover { transform: translateY(-3px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); border-color: #c4d9ff; }
    .detail-label { font-weight: 600; color: #5a5c69; font-size: 0.85rem; margin-bottom: 0.3rem; display: block; }
    .detail-value { color: #333; font-size: 1rem; }
    .full-width { grid-column: 1 / -1; }

    /* --- NEW & UPDATED MODAL STYLES FROM ADMIN/WARDEN FILE --- */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; color: #333; }
    span.close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-step { display: none; }
    .modal-step.active { display: block; }
    .modal .form-group { margin-bottom: 1rem; }
    .modal label { text-align: left; display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
    .modal input[type="text"], .modal input[type="password"], .modal input[type="email"] { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box; transition: all 0.2s; }
    .modal input:focus { outline: none; border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2); }
    .modal-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem; }
    .modal .btn { width: 100%; padding: 0.75rem; font-size: 0.9rem; font-weight: 600; border-radius: 5px; border: none; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.2s ease-in-out; display: inline-flex; align-items: center; justify-content: center; }
    .modal .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .modal .btn-secondary { background-color: #f8f9fc; color: #5a5c69; border: 1px solid #d1d3e2; }
    .modal .btn-secondary:hover { background-color: #e3e6f0; }
    .modal .btn-danger { background-color: #e74a3b; color: #fff; }
    .modal .btn-primary { background-color: #0d6efd; color: #fff; }
    .modal .btn-submit { background-color: #1cc88a; color: white; }
    .password-group { position: relative; }
    .password-toggle { position: absolute; top: 65%; right: 15px; transform: translateY(-50%); cursor: pointer; color: #858796; user-select: none; }
    .modal input[type="password"] { padding-right: 40px; }
    #modal-message { padding: 10px; border-radius: 5px; margin-top: 15px; font-weight: 500; display: none; text-align: center; }
    #modal-message.success { background-color: #d1fae5; color: #065f46; }
    #modal-message.error { background-color: #fee2e2; color: #991b1b; }
    .resend-container { text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e3e6f0; }
    #resend-otp { color: #0d6efd; text-decoration: none; font-weight: 600; cursor: pointer; transition: color 0.2s; }
    #resend-otp:hover { text-decoration: underline; }
    #resend-otp.disabled { color: #858796; cursor: not-allowed; text-decoration: none; }
</style>
<!-- === END STYLES === -->

<!-- HTML STRUCTURE (UNCHANGED) -->
<div class="settings-container">
    <aside class="profile-sidebar">
        <img src="https://placehold.co/120x120/EBF2FF/333333?text={{ substr($student->full_name ?? 'S', 0, 1) }}" alt="Profile Picture" class="profile-picture">
        <h4>{{ $student->full_name ?? 'Student Name' }}</h4>
        <p>{{ Auth::user()->email }}</p>

        <nav class="profile-sidebar-nav">
            <ul>
                <li>
                    <a href="{{ route('student.settings.profile') }}"><i class="fas fa-edit"></i><span>Update Profile</span></a>
                </li>
                <li>
                    <button id="changePasswordBtn"><i class="fas fa-key"></i><span>Change Password</span></button>
                </li>
                <li>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="width: 100%;">
                        @csrf
                        <button type="button" id="logoutBtn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="settings-main-content">
        <div class="settings-card">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="settings-section">
                <h5><i class="fas fa-user-circle"></i>Personal Information</h5>
                @if($student && $student->full_name)
                    <div class="details-grid">
                        <div class="detail-item"><span class="detail-label">Full Name</span><span class="detail-value">{{ $student->full_name }}</span></div>
                        <div class="detail-item"><span class="detail-label">Registration No</span><span class="detail-value">{{ $student->reg_no }}</span></div>
                        <div class="detail-item"><span class="detail-label">Telephone</span><span class="detail-value">{{ $student->telephone_number }}</span></div>
                        <div class="detail-item"><span class="detail-label">NIC</span><span class="detail-value">{{ $student->nic }}</span></div>
                        <div class="detail-item"><span class="detail-label">Faculty</span><span class="detail-value">{{ $student->faculty }}</span></div>
                        <div class="detail-item full-width"><span class="detail-label">Address</span><span class="detail-value">{{ $student->address }}</span></div>
                    </div>
                @else
                    <p>Your profile is not yet completed. <a href="{{ route('student.settings.profile') }}">Please update your profile.</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- === END HTML STRUCTURE === -->


<!-- === NEW MODAL HTML FROM ADMIN/WARDEN FILE === -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Change Password</h3>
            <span class="close-button">&times;</span>
        </div>
        
        <div id="step-email" class="modal-step active">
            <p>Enter your account email to receive a verification OTP.</p>
            <form id="sendOtpForm">
                <div class="form-group"><label>Email Address</label><input type="email" name="email" value="{{ Auth::user()->email }}" readonly style="background:#eaecf4;"></div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary close-button">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send OTP</button>
                </div>
            </form>
        </div>

        <div id="step-otp" class="modal-step">
            <p>An OTP has been sent. It will expire in 5 minutes.</p>
            <form id="verifyOtpForm">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                <div class="form-group"><label>6-Digit OTP</label><input type="text" name="otp" required></div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary close-button">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm OTP</button>
                </div>
                <div class="resend-container">
                    <a href="#" id="resend-otp">Resend OTP</a>
                </div>
            </form>
        </div>

        <div id="step-password" class="modal-step">
            <p>OTP verified! Set a new, strong password.</p>
            <!-- IMPORTANT: The action route is pointed to the STUDENT's change password route -->
            <form id="resetPasswordForm" action="{{ route('student.settings.changePassword') }}" method="POST">
                @csrf
                <div class="form-group password-group">
                    <label>New Password</label>
                    <input type="password" name="password" required>
                    <span class="password-toggle" title="Show/Hide Password">&#128065;</span>
                </div>
                <div class="form-group password-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" required>
                    <span class="password-toggle" title="Show/Hide Password">&#128065;</span>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary close-button">Cancel</button>
                    <button type="submit" class="btn btn-submit">Change Password</button>
                </div>
            </form>
        </div>

        <div id="step-success" class="modal-step">
            <h3 style="color: #1cc88a;">Success!</h3>
            <p>Your password was changed successfully.</p>
            <div class="modal-buttons" style="grid-template-columns: 1fr; justify-content: center;">
                <button type="button" id="finalOkBtn" class="btn btn-primary" style="max-width: 120px; margin: auto;">OK</button>
            </div>
        </div>
        
        <div id="modal-message"></div>
    </div>
</div>

<div id="logoutConfirmModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Logout</h3>
        </div>
        <p style="text-align: center; font-size: 1.1rem; padding: 1rem 0;">
            Are you sure you want to log out?
        </p>
        <div class="modal-buttons">
            <button type="button" id="cancelLogoutBtn" class="btn btn-secondary">Cancel</button>
            <button type="button" id="confirmLogoutBtn" class="btn btn-danger">Yes, Logout</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- === NEW JAVASCRIPT FROM ADMIN/WARDEN FILE (WITH STUDENT ROUTES) === -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- SCRIPT FOR PASSWORD CHANGE MODAL ---
    const modal = document.getElementById('passwordModal');
    const openBtn = document.getElementById('changePasswordBtn');
    const closeBtns = document.querySelectorAll('.close-button');
    const messageDiv = document.getElementById('modal-message');
    const stepEmail = document.getElementById('step-email');
    const stepOtp = document.getElementById('step-otp');
    const stepPassword = document.getElementById('step-password');
    const stepSuccess = document.getElementById('step-success');
    const sendOtpForm = document.getElementById('sendOtpForm');
    const verifyOtpForm = document.getElementById('verifyOtpForm');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const resendOtpBtn = document.getElementById('resend-otp');
    const finalOkBtn = document.getElementById('finalOkBtn');
    let currentEmail = "{{ Auth::user()->email }}";
    let timer;

    function showStep(stepToShow) {
        [stepEmail, stepOtp, stepPassword, stepSuccess].forEach(step => {
            if(step) step.classList.remove('active');
        });
        if(stepToShow) stepToShow.classList.add('active');
        if(messageDiv) messageDiv.style.display = 'none';
    }
    function showMessage(type, text) {
        if (!messageDiv) return;
        messageDiv.className = '';
        messageDiv.id = 'modal-message';
        messageDiv.classList.add(type);
        messageDiv.textContent = text;
        messageDiv.style.display = 'block';
    }
    function resetModal() {
        showStep(stepEmail);
        clearInterval(timer);
        if(resendOtpBtn) {
            resendOtpBtn.classList.remove('disabled');
            resendOtpBtn.textContent = 'Resend OTP';
        }
    }
    function startResendTimer() {
        let seconds = 120;
        if(resendOtpBtn) {
            resendOtpBtn.classList.add('disabled');
            timer = setInterval(() => {
                seconds--;
                resendOtpBtn.textContent = `Resend OTP in ${seconds}s`;
                if (seconds <= 0) {
                    clearInterval(timer);
                    resendOtpBtn.classList.remove('disabled');
                    resendOtpBtn.textContent = 'Resend OTP';
                }
            }, 1000);
        }
    }
    function togglePasswordVisibility(e) {
        const input = e.target.previousElementSibling;
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    if(openBtn) openBtn.addEventListener('click', () => { resetModal(); modal.style.display = 'block'; });
    if(closeBtns) closeBtns.forEach(btn => btn.addEventListener('click', () => modal.style.display = 'none'));
    document.querySelectorAll('.password-toggle').forEach(el => el.addEventListener('click', togglePasswordVisibility));

    if(sendOtpForm) sendOtpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showMessage('success', 'Sending...');
        // IMPORTANT: Using STUDENT route
        fetch('{{ route("student.settings.sendOtp") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ email: currentEmail })
        }).then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
        .then(data => {
            showStep(stepOtp);
            showMessage('success', data.message);
            startResendTimer();
        }).catch(err => showMessage('error', err.message || 'An error occurred.'));
    });
    
    if(resendOtpBtn) {
        resendOtpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) return;
            sendOtpForm.dispatchEvent(new Event('submit', {cancelable: true}));
        });
    }

    if(verifyOtpForm) verifyOtpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showMessage('success', 'Verifying...');
        // IMPORTANT: Using STUDENT route
        fetch('{{ route("student.settings.verifyOtp") }}', {
            method: 'POST',
            body: new FormData(this),
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
        .then(data => {
            showStep(stepPassword);
            showMessage('success', 'OTP Verified!');
        }).catch(err => showMessage('error', err.message || 'An error occurred.'));
    });

    if(resetPasswordForm) resetPasswordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showMessage('success', 'Processing...');
        // IMPORTANT: Using STUDENT route (already correct in the HTML action)
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('#resetPasswordForm [name=_token]').value }
        }).then(res => {
            if (!res.ok) return res.json().then(err => Promise.reject(err));
            return res.json();
        }).then(data => {
            showStep(stepSuccess);
        }).catch(err => {
            const errorText = err.errors ? Object.values(err.errors).flat().join(' ') : (err.message || 'An error occurred.');
            showMessage('error', errorText);
        });
    });

    if(finalOkBtn) finalOkBtn.addEventListener('click', function() {
        window.location.reload();
    });

    // --- SCRIPT FOR LOGOUT CONFIRMATION ---
    const logoutModal = document.getElementById('logoutConfirmModal');
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');
    const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');
    const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(event) {
            event.preventDefault(); 
            logoutModal.style.display = 'block';
        });
    }
    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', function() {
            logoutModal.style.display = 'none';
        });
    }
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', function() {
            logoutForm.submit(); 
        });
    }
});
</script>
@endpush

