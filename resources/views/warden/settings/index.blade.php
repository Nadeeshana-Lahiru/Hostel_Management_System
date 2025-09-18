@extends('warden.layout')
@section('title', 'Settings')
@section('page-title', 'Account Settings')

@section('content')
<style>
    .settings-card { background-color: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 600px; margin: auto; }
    .settings-section { border-bottom: 1px solid #e3e6f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
    .settings-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .settings-section h5 { font-size: 1.2rem; font-weight: 600; color: #5a5c69; margin-bottom: 1rem; }
    .account-email { font-size: 1rem; color: #333; background: #f8f9fc; padding: 0.75rem; border-radius: 5px; border: 1px solid #e3e6f0; }
    
    .btn { width: 100%; padding: 0.75rem; font-size: 1rem; font-weight: 600; border-radius: 5px; border: none; cursor: pointer; text-align: center; transition: all 0.2s; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .btn-warning { background-color: #f6c23e; color: #fff; }
    .btn-danger { background-color: #e74a3b; color: #fff; }

    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; color: #333; }
    .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-step { display: none; }
    .modal-step.active { display: block; }
    
    .modal .form-group { margin-bottom: 1rem; }
    .modal label { text-align: left; display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
    .modal input[type="text"], .modal input[type="password"], .modal input[type="email"] {
        width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;
        box-sizing: border-box; transition: all 0.2s;
    }
    .modal input:focus { outline: none; border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2); }

    .modal-buttons { display: flex; gap: 1rem; margin-top: 1.5rem; }
    .modal .btn { flex-grow: 1; padding: 0.75rem; font-size: 0.9rem; }
    .btn-primary { background-color: #4e73df; color: white; }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-submit { background-color: #1cc88a; color: white; }
    
    #modal-message { padding: 10px; border-radius: 5px; margin-top: 15px; font-weight: 500; display: none; text-align: center; }
    #modal-message.success { background-color: #d1fae5; color: #065f46; }
    #modal-message.error { background-color: #fee2e2; color: #991b1b; }

    .alert-success {
        padding: 1rem; margin-bottom: 1.5rem; border-radius: 5px;
        background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; font-weight: 500;
    }
    
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


    .password-group {
        position: relative;
    }
    .password-toggle {
        position: absolute;
        top: 65%; 
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #858796;
        user-select: none; 
    }
    .modal input[type="password"] {
        padding-right: 40px;
    }
</style>

<div class="settings-card">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="settings-section">
        <h5>Account Details</h5>
        <p class="account-email"><strong>Email:</strong> {{ Auth::user()->email }}</p>
    </div>

    <div class="settings-section">
        <h5>Actions</h5>
        <button id="changePasswordBtn" class="btn btn-warning">Change Password</button>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 1rem;">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>

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
                <div class="modal-buttons"><button type="button" class="btn btn-secondary close-button">Cancel</button><button type="submit" class="btn btn-primary">Send OTP</button></div>
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
            <form id="resetPasswordForm" action="{{ route('warden.settings.changePassword') }}" method="POST">
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
            <div class="modal-buttons" style="justify-content: center;">
                <button type="button" id="finalOkBtn" class="btn btn-primary" style="flex-grow: 0;">OK</button>
            </div>
        </div>
        
        <div id="modal-message"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('passwordModal');
    const openBtn = document.getElementById('changePasswordBtn');
    const closeBtns = document.querySelectorAll('.close-button');
    const messageDiv = document.getElementById('modal-message');
    const stepEmail = document.getElementById('step-email');
    const stepOtp = document.getElementById('step-otp');
    const stepPassword = document.getElementById('step-password');
    const sendOtpForm = document.getElementById('sendOtpForm');
    const verifyOtpForm = document.getElementById('verifyOtpForm');
    const resendOtpBtn = document.getElementById('resend-otp');
    const finalOkBtn = document.getElementById('finalOkBtn');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const stepSuccess = document.getElementById('step-success');
    let currentEmail = "{{ Auth::user()->email }}";
    let timer;

    function showStep(stepToShow) {
        [stepEmail, stepOtp, stepPassword].forEach(step => step.classList.remove('active'));
        stepToShow.classList.add('active');
        messageDiv.style.display = 'none';
    }
    function showMessage(type, text) {
        messageDiv.className = type;
        messageDiv.id = 'modal-message';
        messageDiv.textContent = text;
        messageDiv.style.display = 'block';
    }
    function resetModal() {
        showStep(stepEmail);
        sendOtpForm.reset();
        verifyOtpForm.reset();
        document.getElementById('resetPasswordForm').reset();
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
    function togglePasswordVisibility(e) {
        const input = e.target.previousElementSibling;
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    openBtn.addEventListener('click', () => { resetModal(); modal.style.display = 'block'; });
    closeBtns.forEach(btn => btn.addEventListener('click', () => modal.style.display = 'none'));
    //window.onclick = (event) => { if (event.target == modal) { modal.style.display = 'none'; resetModal(); } };
    document.querySelectorAll('.password-toggle').forEach(el => el.addEventListener('click', togglePasswordVisibility));

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
    
    if(resendOtpBtn) {
        resendOtpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) return;
            // Since the email is fixed, we can just trigger the form submission again
            sendOtpForm.dispatchEvent(new Event('submit', {cancelable: true}));
        });
    }

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

    resetPasswordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showMessage('success', 'Processing...');
        fetch('{{ route("warden.settings.changePassword") }}', {
            method: 'POST',
            body: new FormData(this),
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('#resetPasswordForm [name=_token]').value }
        }).then(res => {
            if (!res.ok) return res.json().then(err => Promise.reject(err));
            return res.json();
        }).then(data => {
            // On success, show the new success step
            showStep(stepSuccess);
        }).catch(err => {
            const errorText = err.errors ? err.errors.password[0] : (err.message || 'An error occurred.');
            showMessage('error', errorText);
        });
    });

    // NEW: When the final "OK" button is clicked, reload the page
    finalOkBtn.addEventListener('click', function() {
        // The controller flashed the success message, so a simple reload will show it.
        window.location.reload();
    });
});
</script>
@endpush