<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; margin: 0; display: flex;
            flex-direction: column; justify-content: center; align-items: center; min-height: 100vh;
            padding: 2rem; box-sizing: border-box;
        }
        .main-title { font-size: 2.5rem; font-weight: 600; color: #333; margin-bottom: 2rem; text-align: center; }
        .login-container { display: flex; justify-content: center; gap: 2rem; width: 100%; max-width: 1200px; }
        .login-card {
            background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            width: 100%; max-width: 350px; border-top: 4px solid #ccc; transition: all 0.3s ease-in-out;
        }
        .login-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.15); }
        .card-header { display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem; }
        .card-header .icon { padding: 10px; border-radius: 50%; display: inline-flex; }
        .card-header h3 { margin: 0; font-size: 1.5rem; font-weight: 600; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; text-align: left; }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;
            box-sizing: border-box; transition: all 0.2s;
        }
        .btn-login {
            width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 1rem;
            font-weight: 600; color: white; cursor: pointer; transition: background-color 0.3s;
        }
        .forgot-password { text-align: right; margin-top: 0.75rem; }
        .forgot-password button {
            background: none; border: none; padding: 0; font-family: inherit; font-size: 0.9rem;
            cursor: pointer; text-decoration: none; font-weight: 500;
        }

        /* --- THEME COLORS --- */
        .admin-theme { border-top-color: #4e73df; }
        .admin-theme .icon { background-color: #eaecf4; }
        .admin-theme h3, .admin-theme .forgot-password button { color: #4e73df; }
        .admin-theme .btn-login { background-color: #4e73df; }
        .admin-theme input:focus { border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2); }
        
        .warden-theme { border-top-color: #6610f2; }
        .warden-theme .icon { background-color: #e5d9fa; }
        .warden-theme h3, .warden-theme .forgot-password button { color: #6610f2; }
        .warden-theme .btn-login { background-color: #6610f2; }
        .warden-theme input:focus { border-color: #6610f2; box-shadow: 0 0 0 3px rgba(102, 16, 242, 0.2); }

        .student-theme { border-top-color: #1cc88a; }
        .student-theme .icon { background-color: #d1fae5; }
        .student-theme h3, .student-theme .forgot-password button { color: #1cc88a; }
        .student-theme .btn-login { background-color: #1cc88a; }
        .student-theme input:focus { border-color: #1cc88a; box-shadow: 0 0 0 3px rgba(28, 200, 138, 0.2); }

        .error-message { color: #dc3545; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 1rem; text-align: center; }

        /* --- BEAUTIFUL MODAL STYLES --- */
        /* --- UPDATED MODAL & FORM STYLES --- */
        .forgot-password button { background: none; border: none; padding: 0; font-family: inherit; font-size: 0.9rem; cursor: pointer; text-decoration: none; font-weight: 500; }
        .admin-theme .forgot-password button { color: #4e73df; }
        .warden-theme .forgot-password button { color: #6610f2; }
        .student-theme .forgot-password button { color: #1cc88a; }

        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
        @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; color: #333; }
        .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .modal-step { display: none; }
        .modal-step.active { display: block; }
        .modal-buttons { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .modal .btn { flex-grow: 1; padding: 0.75rem; font-size: 0.9rem; font-weight: 600; border-radius: 8px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
        #modal-message { padding: 10px; border-radius: 5px; margin-top: 15px; font-weight: 500; display: none; text-align: center; }
        #modal-message.success { background-color: #d1fae5; color: #065f46; }
        #modal-message.error { background-color: #fee2e2; color: #991b1b; }
        #resend-otp { color: #4e73df; text-decoration: none; font-weight: 600; cursor: pointer; }
        #resend-otp.disabled { color: #858796; cursor: not-allowed; }
        .password-group { position: relative; }
        .password-toggle { position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer; color: #858796; }
        
        /* Responsive Media Query */
        @media (max-width: 1150px) { .login-container { flex-direction: column; } }
    </style>
</head>
<body>
    <h1 class="main-title">Login to Your Hostel</h1>

    @if(session('success'))
        <div class="error-message" style="background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; max-width: 1160px; width: 100%;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error-message" style="max-width: 1160px; width: 100%; box-sizing: border-box;">{{ session('error') }}</div>
    @endif

    <div class="login-container">
        <div class="login-card admin-theme">
            <div class="card-header">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4e73df" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                <h3>Admin Login</h3>
            </div>
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="user_type" value="admin">
                <div class="form-group"><label>Username (Email)</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="btn-login">Login as Admin</button>
                <div class="forgot-password"><button type="button" class="forgot-password-btn">Forgot Password?</button></div>
            </form>
        </div>

        <div class="login-card warden-theme">
            <div class="card-header">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6610f2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                <h3>Warden Login</h3>
            </div>
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="user_type" value="warden">
                <div class="form-group"><label>Username (Email)</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="btn-login">Login as Warden</button>
                <div class="forgot-password"><button type="button" class="forgot-password-btn">Forgot Password?</button></div>
            </form>
        </div>

        <div class="login-card student-theme">
            <div class="card-header">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1cc88a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                <h3>Student Login</h3>
            </div>
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="user_type" value="student">
                <div class="form-group"><label>Username (Reg No)</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="btn-login">Login as Student</button>
                <div class="forgot-password"><button type="button" class="forgot-password-btn">Forgot Password?</button></div>
            </form>
        </div>
    </div>

   <div id="forgotPasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Reset Password</h3>
                <span class="close-button">&times;</span>
            </div>
            
            <div id="step-email" class="modal-step active">
                <p>Enter your account email to receive a verification OTP.</p>
                <form id="sendOtpForm">
                    <div class="form-group"><label for="email">Email Address</label><input type="email" name="email" required></div>
                    <div class="modal-buttons"><button type="button" class="btn btn-secondary close-button">Cancel</button><button type="submit" class="btn btn-primary">Send OTP</button></div>
                </form>
            </div>

            <div id="step-otp" class="modal-step">
                <p>An OTP has been sent. It will expire in 5 minutes.</p>
                <form id="verifyOtpForm">
                    <input type="hidden" name="email">
                    <div class="form-group"><label>6-Digit OTP</label><input type="text" name="otp" required></div>
                    <div class="modal-buttons"><button type="button" class="btn btn-secondary close-button">Cancel</button><button type="submit" class="btn btn-primary">Confirm OTP</button></div>
                    <div style="text-align: center; margin-top: 1rem;"><a href="#" id="resend-otp">Resend OTP</a></div>
                </form>
            </div>

            <div id="step-password" class="modal-step">
                <p>OTP verified! You can now set a new, strong password.</p>
                <form id="resetPasswordForm" action="{{ route('password.update') }}" method="POST">
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
                    <div class="modal-buttons"><button type="button" class="btn btn-secondary close-button">Cancel</button><button type="submit" class="btn btn-submit">Change Password</button></div>
                </form>
            </div>
            
            <div id="modal-message"></div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('forgotPasswordModal');
    const openBtns = document.querySelectorAll('.forgot-password-btn');
    const closeBtns = document.querySelectorAll('.close-button');
    const messageDiv = document.getElementById('modal-message');

    const stepEmail = document.getElementById('step-email');
    const stepOtp = document.getElementById('step-otp');
    const stepPassword = document.getElementById('step-password');
    
    const sendOtpForm = document.getElementById('sendOtpForm');
    const verifyOtpForm = document.getElementById('verifyOtpForm');
    const resendOtpBtn = document.getElementById('resend-otp');

    let currentEmail = '';
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
        resendOtpBtn.classList.remove('disabled');
        resendOtpBtn.textContent = 'Resend OTP';
    }

    function startResendTimer() {
        let seconds = 120; // 2 minutes
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

    openBtns.forEach(btn => btn.addEventListener('click', () => modal.style.display = 'block'));
    closeBtns.forEach(btn => btn.addEventListener('click', () => modal.style.display = 'none'));
    window.onclick = (event) => { if (event.target == modal) { modal.style.display = 'none'; resetModal(); } };
    document.querySelectorAll('.password-toggle').forEach(el => el.addEventListener('click', togglePasswordVisibility));

    sendOtpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        currentEmail = this.querySelector('input[name="email"]').value;
        showMessage('success', 'Sending...');
        fetch('{{ route("password.email") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ email: currentEmail })
        }).then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
        .then(data => {
            verifyOtpForm.querySelector('input[name="email"]').value = currentEmail;
            showStep(stepOtp);
            showMessage('success', data.message);
            startResendTimer();
        }).catch(err => showMessage('error', err.message || 'An error occurred.'));
    });
    
    resendOtpBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (this.classList.contains('disabled')) return;
        sendOtpForm.dispatchEvent(new Event('submit', {cancelable: true}));
    });

    verifyOtpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showMessage('success', 'Verifying...');
        fetch('{{ route("password.verifyOtp") }}', {
            method: 'POST',
            body: new FormData(this),
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
        .then(data => {
            showStep(stepPassword);
        }).catch(err => showMessage('error', err.message || 'An error occurred.'));
    });
});
</script>

</body>
</html>