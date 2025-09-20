@extends('warden.layout')
@section('title', 'Settings')
@section('page-title', 'Account Settings')

@section('content')
<!-- === STYLES COPIED FROM STUDENT SETTINGS PAGE === -->
<style>
    /* Main container to create the two-column layout */
    .settings-container {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }

    /* Left column for profile picture and basic info */
    .profile-sidebar {
        flex: 0 0 280px; /* Fixed width for the sidebar */
        background-color: #fff;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        text-align: center;
        position: sticky; /* Makes it stick on scroll */
        top: 20px;
    }
    .profile-picture {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1rem;
        object-fit: cover;
        border: 4px solid #e9f2ff;
    }
    .profile-sidebar h4 {
        margin: 0.5rem 0 0.25rem;
        font-size: 1.25rem;
        color: #333;
    }
    .profile-sidebar p {
        margin: 0;
        color: #888;
        font-size: 0.9rem;
    }

    /* Styling for the navigation menu in the sidebar */
    .profile-sidebar-nav {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eef2f7;
    }
    .profile-sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
    }
    .profile-sidebar-nav li a,
    .profile-sidebar-nav li button {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 8px;
        border-radius: 8px;
        text-decoration: none;
        background-color: transparent;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        font-family: inherit;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    /* Default Colors for sidebar buttons */
    .profile-sidebar-nav li a { color: #0d6efd; }       /* Update Profile: Blue */
    #changePasswordBtn { color: #ffb300; }  /* Change Password: Yellow */
    #logoutBtn { color: #e53935; }          /* Logout: Red */

    /* Hover Backgrounds for sidebar buttons */
    .profile-sidebar-nav li a:hover { background-color: #e9f2ff; }
    #changePasswordBtn:hover { background-color: #fff9e1; }
    #logoutBtn:hover { background-color: #ffebee; }

    .profile-sidebar-nav li a:hover i,
    .profile-sidebar-nav li button:hover i {
        color: inherit;
    }
    .profile-sidebar-nav i {
        margin-right: 15px;
        width: 20px;
        text-align: center;
        font-size: 1rem;
    }

    /* Right column for the main content and forms */
    .settings-main-content {
        flex-grow: 1;
    }

    /* Styling for the main card holding the details */
    .settings-card { 
        background-color: #fff; 
        padding: 2.5rem; 
        border-radius: 12px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .settings-section h5 { 
        font-size: 1.4rem; 
        font-weight: 600; 
        color: #333; 
        margin-bottom: 1.5rem; 
        display: flex;
        align-items: center;
    }
    .settings-section h5 i {
        margin-right: 12px;
        color: #0d6efd;
    }

    /* Redesigned details grid */
    .details-grid { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem; 
    }
    .detail-item {
        background: #f8f9fc;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        transition: all 0.2s ease;
    }
    .detail-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        border-color: #c4d9ff;
    }
    .detail-label { 
        font-weight: 600; 
        color: #5a5c69; 
        font-size: 0.85rem; 
        margin-bottom: 0.3rem; 
        display: block;
    }
    .detail-value {
        color: #333;
        font-size: 1rem;
    }
    .full-width {
        grid-column: 1 / -1;
    }

    /* Button styles for modals */
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem; font-size: 0.95rem; font-weight: 600; border-radius: 8px; border: 1px solid transparent; cursor: pointer; text-align: center; transition: all 0.2s; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .btn-primary { background-color: #0d6efd; color: #fff; border-color: #0d6efd; }
    .btn-danger { background-color: #dc3545; color: #fff; border-color: #dc3545; }
    .btn-secondary { background-color: #f8f9fc; color: #5a5c69; border: 1px solid #d1d3e2; }
    .btn-secondary:hover { background-color: #e3e6f0; }

    /* Modal styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; color: #333; }
    span.close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-step { display: none; }
    .modal-step.active { display: block; }
</style>
<!-- === END STYLES === -->

<!-- === NEW HTML STRUCTURE FOR WARDEN === -->
<div class="settings-container">
    <aside class="profile-sidebar">
        <img src="https://placehold.co/120x120/EBF2FF/333333?text={{ substr($warden->full_name ?? 'W', 0, 1) }}" alt="Profile Picture" class="profile-picture">
        <h4>{{ $warden->full_name ?? 'Warden Name' }}</h4>
        <p>{{ Auth::user()->email }}</p>

        <nav class="profile-sidebar-nav">
            <ul>
                <li>
                    <a href="{{ route('warden.settings.profile') }}"><i class="fas fa-edit"></i><span>Update Profile</span></a>
                </li>
                <li>
                    <button id="changePasswordBtn"><i class="fas fa-key"></i><span>Change Password</span></button>
                </li>
                <li>
                    <button id="logoutBtn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></button>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="settings-main-content">
        <div class="settings-card">
            @if(session('success'))
                <div class="alert alert-success" style="padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; font-weight: 500;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" style="padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-weight: 500;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="settings-section">
                <h5><i class="fas fa-user-circle"></i>Personal Information</h5>
                @if($warden && $warden->full_name)
                    <div class="details-grid">
                        <div class="detail-item"><span class="detail-label">Full Name</span><span class="detail-value">{{ $warden->full_name }}</span></div>
                        <div class="detail-item"><span class="detail-label">Name with Initials</span><span class="detail-value">{{ $warden->initial_name }}</span></div>
                        <div class="detail-item"><span class="detail-label">Telephone</span><span class="detail-value">{{ $warden->telephone_number }}</span></div>
                        <div class="detail-item"><span class="detail-label">NIC</span><span class="detail-value">{{ $warden->nic }}</span></div>
                        <div class="detail-item"><span class="detail-label">Date of Birth</span><span class="detail-value">{{ \Carbon\Carbon::parse($warden->dob)->format('F j, Y') }}</span></div>
                        <div class="detail-item full-width"><span class="detail-label">Address</span><span class="detail-value">{{ $warden->address }}</span></div>
                    </div>
                @else
                    <p>Your profile is not yet completed. <a href="{{ route('warden.settings.profile') }}">Please update your profile.</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- === END NEW HTML STRUCTURE === -->


<!-- Modals (The structure is updated to use the new button styles and layout) -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Change Password</h3>
            <span class="close-button">&times;</span>
        </div>
        
        <div id="step-email" class="modal-step active">
            <p>Enter your account email to receive a verification OTP.</p>
            <form id="sendOtpForm">
                <div class="form-group"><label>Email Address</label><input type="email" name="email" value="{{ Auth::user()->email }}" readonly style="background:#eaecf4; width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box;"></div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;"><button type="button" class="btn btn-secondary close-button">Cancel</button><button type="submit" class="btn btn-primary">Send OTP</button></div>
            </form>
        </div>

        <div id="step-otp" class="modal-step">
            <p>An OTP has been sent. It will expire in 5 minutes.</p>
             <form id="verifyOtpForm">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                <div class="form-group"><label>6-Digit OTP</label><input type="text" name="otp" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box;"></div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
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
            <form id="resetPasswordForm" action="{{ route('warden.settings.changePassword') }}" method="POST">
                @csrf
                <div class="form-group"><label>New Password</label><input type="password" name="password" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box;"></div>
                <div class="form-group"><label>Confirm New Password</label><input type="password" name="password_confirmation" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box;"></div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-secondary close-button">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
        <div id="modal-message"></div>
    </div>
</div>

<div id="logoutConfirmModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h3>Confirm Logout</h3></div>
        <p style="text-align: center; font-size: 1.1rem; padding: 1rem 0;">Are you sure you want to log out?</p>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem; justify-content: center;">
            <button type="button" id="cancelLogoutBtn" class="btn btn-secondary">Cancel</button>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" id="confirmLogoutBtn" class="btn btn-danger">Yes, Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- 1. ELEMENT SELECTORS ---
    // All variables are declared once at the top for clarity.
    
    // Password Modal Elements
    const passwordModal = document.getElementById('passwordModal');
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const closePasswordModalBtns = document.querySelectorAll('#passwordModal .close-button');
    const messageDiv = document.getElementById('modal-message');
    const stepEmail = document.getElementById('step-email');
    const stepOtp = document.getElementById('step-otp');
    const stepPassword = document.getElementById('step-password');
    const sendOtpForm = document.getElementById('sendOtpForm');
    const verifyOtpForm = document.getElementById('verifyOtpForm');
    const resendOtpBtn = document.getElementById('resend-otp');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    
    // Logout Modal Elements
    const logoutModal = document.getElementById('logoutConfirmModal');
    const logoutBtn = document.getElementById('logoutBtn');
    const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');

    // Shared variables
    let currentEmail = "{{ Auth::user()->email }}";
    let timer;

    // --- 2. PASSWORD MODAL FUNCTIONS ---
    // These functions control the multi-step password change process.

    function showStep(stepToShow) {
        [stepEmail, stepOtp, stepPassword].forEach(step => step.classList.remove('active'));
        stepToShow.classList.add('active');
        if (messageDiv) messageDiv.style.display = 'none';
    }

    function showMessage(type, text) {
        if (!messageDiv) return;
        messageDiv.className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
        messageDiv.textContent = text;
        messageDiv.style.display = 'block';
    }

    function resetPasswordModal() {
        showStep(stepEmail);
        if (sendOtpForm) sendOtpForm.reset();
        if (verifyOtpForm) verifyOtpForm.reset();
        if (resetPasswordForm) resetPasswordForm.reset();
        clearInterval(timer);
        if(resendOtpBtn) {
            resendOtpBtn.classList.remove('disabled');
            resendOtpBtn.textContent = 'Resend OTP';
        }
    }

    function startResendTimer() {
        let seconds = 120;
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

    // --- 3. EVENT LISTENERS ---

    // --- Password Modal Listeners ---
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', () => { 
            resetPasswordModal(); 
            passwordModal.style.display = 'block'; 
        });
    }

    if (closePasswordModalBtns) {
        closePasswordModalBtns.forEach(btn => btn.addEventListener('click', () => {
            passwordModal.style.display = 'none';
        }));
    }

    if (sendOtpForm) {
        sendOtpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showMessage('success', 'Sending...');
            fetch('{{ route("warden.settings.sendOtp") }}', {
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
    }
    
    if (resendOtpBtn) {
        resendOtpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) return;
            sendOtpForm.dispatchEvent(new Event('submit', {cancelable: true}));
        });
    }

    if (verifyOtpForm) {
        verifyOtpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showMessage('success', 'Verifying...');
            fetch('{{ route("warden.settings.verifyOtp") }}', {
                method: 'POST',
                body: new FormData(this),
                headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }).then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
            .then(data => {
                showStep(stepPassword);
            }).catch(err => showMessage('error', err.message || 'An error occurred.'));
        });
    }

    if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showMessage('success', 'Processing...');
            fetch(this.action, { // Use the form's action attribute
                method: 'POST',
                body: new FormData(this),
                headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('#resetPasswordForm [name=_token]').value }
            }).then(res => {
                if (!res.ok) return res.json().then(err => Promise.reject(err));
                return res.json();
            }).then(data => {
                // On success, simply reload the page. 
                // The controller will have flashed a session message which will now be visible.
                window.location.reload();
            }).catch(err => {
                const errorText = err.errors ? err.errors.password[0] : (err.message || 'An error occurred.');
                showMessage('error', errorText);
            });
        });
    }

    // --- Logout Modal Listeners ---
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            logoutModal.style.display = 'block'; // Show the logout confirmation
        });
    }

    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', function() {
            logoutModal.style.display = 'none'; // Hide the modal
        });
    }

    // The "Yes, Logout" button has type="submit" and is inside the logout form.
    // It will work automatically without needing any extra JavaScript.

    // --- Close modal if clicking outside of it ---
    window.addEventListener('click', function(event) {
        if (event.target == passwordModal) {
            passwordModal.style.display = 'none';
        }
        if (event.target == logoutModal) {
            logoutModal.style.display = 'none';
        }
    });
});
</script>
@endpush