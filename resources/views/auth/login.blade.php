<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef2f7;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 950px;
            margin: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-image-panel {
            flex-basis: 45%;
            background-color: #f8f9fc;
        }
        .login-image-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .login-form-panel {
            flex-basis: 55%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form-panel h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .login-form-panel p {
            text-align: center;
            color: #777;
            margin-bottom: 2.5rem;
        }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: all 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2);
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            background-color: #4e73df;
            transition: background-color 0.3s;
        }
        .btn-login:hover { background-color: #2e59d9; }
        .forgot-password { text-align: right; margin-top: 1rem; }
        .forgot-password-btn {
            background: none; border: none; padding: 0; font-family: inherit; font-size: 0.9rem;
            cursor: pointer; text-decoration: none; font-weight: 500; color: #4e73df;
        }
        .error-message {
            color: #dc3545; background-color: #f8d7da; border: 1px solid #f5c6cb;
            padding: 1rem; border-radius: 8px;
            position: absolute; top: 20px; left: 50%; transform: translateX(-50%);
            width: 90%; max-width: 910px; box-sizing: border-box; z-index: 1001;
        }
        
        /* --- CORRECT MODAL STYLES --- */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
        @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; color: #333; }
        .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .modal-step { display: none; }
        .modal-step.active { display: block; }
        .modal-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem; }
        .modal .btn { padding: 0.6rem; font-size: 0.9rem; border-radius: 6px; text-transform: capitalize; border: 1px solid transparent; box-sizing: border-box; width:100%; }
        .modal .btn-secondary { background-color: #fff; color: #6c757d; border-color: #ced4da; }
        .modal .btn-secondary:hover { background-color: #f8f9fa; border-color: #b1b9c1; }
        .modal .btn-primary { background-color: #4e73df; color: #fff; border-color: #4e73df; }
        .modal .btn-primary:hover { background-color: #2e59d9; border-color: #2e59d9; }
        .modal .btn-submit { background-color: #1cc88a; color: white; border-color: #1cc88a; }
        #modal-message { padding: 10px; border-radius: 5px; margin-top: 15px; font-weight: 500; display: none; text-align: center; }
        #modal-message.success { background-color: #d1fae5; color: #065f46; }
        #modal-message.error { background-color: #fee2e2; color: #991b1b; }
        .resend-container { text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e3e6f0; }
        #resend-otp { color: #4e73df; text-decoration: none; font-weight: 600; cursor: pointer; }
        #resend-otp.disabled { color: #858796; cursor: not-allowed; }
        .password-group { position: relative; }
        .password-toggle { position: absolute; top: 65%; right: 15px; transform: translateY(-50%); cursor: pointer; color: #858796; }

        @media (max-width: 768px) {
            .login-image-panel { display: none; }
            .login-form-panel { flex-basis: 100%; padding: 2rem; }
            .login-wrapper { margin: 10px; flex-direction: column; }
            .error-message { position: relative; top: 0; left: 0; transform: none; margin-bottom: 1rem; }
        }
    </style>
</head>
<body>

    @if(session('success'))
        <div class="error-message" style="background-color: #d1fae5; color: #065f46; border-color: #a7f3d0;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif

    <div class="login-wrapper">
        <div class="login-image-panel">
            <img src="{{ asset('images/hostel.jpg') }}" alt="A modern hostel building">
        </div>
        <div class="login-form-panel">
            <h2>Welcome Back!</h2>
            <p>Please enter your credentials to log in.</p>
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Email or Registration Number</label>
                    <input type="text" id="username" name="username" placeholder="Enter your email or reg no" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
                <div class="forgot-password">
                    <button type="button" class="forgot-password-btn">Forgot Password?</button>
                </div>
            </form>
        </div>
    </div>

    <!-- CORRECT, FULLY FUNCTIONAL FORGOT PASSWORD MODAL -->
    <div id="forgotPasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header"><h3 id="modalTitle">Reset Password</h3><span class="close-button">&times;</span></div>
            
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
                    <div class="resend-container"><a href="#" id="resend-otp">Resend OTP</a></div>
                </form>
            </div>

            <div id="step-password" class="modal-step">
                <p>OTP verified! Set a new, strong password.</p>
                <form id="resetPasswordForm">
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
            
            <div id="step-success" class="modal-step">
                <h3 style="color: #1cc88a;">Success!</h3>
                <p>You have successfully reset the password. Now log in with your new password.</p>
                <div class="modal-buttons" style="justify-content: center; grid-template-columns: 1fr;">
                    <button type="button" class="btn btn-primary close-button" style="max-width: 120px;">OK</button>
                </div>
            </div>
            
            <div id="modal-message"></div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // CORRECT, FULLY FUNCTIONAL SCRIPT FOR FORGOT PASSWORD MODAL
    const modal = document.getElementById('forgotPasswordModal');
    const openBtns = document.querySelectorAll('.forgot-password-btn');
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
    let currentEmail = '';
    let timer;

    function showStep(stepToShow) {
        [stepEmail, stepOtp, stepPassword, stepSuccess].forEach(step => step.classList.remove('active'));
        stepToShow.classList.add('active');
        if (messageDiv) messageDiv.style.display = 'none';
    }
    function showMessage(type, text) {
        if (!messageDiv) return;
        messageDiv.className = '';
        messageDiv.classList.add(type);
        messageDiv.textContent = text;
        messageDiv.style.display = 'block';
    }
    function resetModal() {
        showStep(stepEmail);
        sendOtpForm.reset();
        verifyOtpForm.reset();
        resetPasswordForm.reset();
        clearInterval(timer);
        if (resendOtpBtn) {
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

    openBtns.forEach(btn => btn.addEventListener('click', () => { resetModal(); modal.style.display = 'block'; }));
    closeBtns.forEach(btn => btn.addEventListener('click', () => { modal.style.display = 'none'; resetModal(); }));

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
    
    if(resendOtpBtn) {
        resendOtpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) return;
            sendOtpForm.dispatchEvent(new Event('submit', {cancelable: true}));
        });
    }

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
            showMessage('success', 'OTP Verified!');
        }).catch(err => showMessage('error', err.message || 'An error occurred.'));
    });

    resetPasswordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showMessage('success', 'Processing...');
        const formData = new FormData(this);
        formData.append('email', currentEmail); // Append the email to the form data
        fetch('{{ route("password.update") }}', {
            method: 'POST',
            body: formData,
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('#resetPasswordForm [name=_token]').value }
        }).then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
        .then(data => {
            showStep(stepSuccess);
        }).catch(err => {
            const errorText = err.errors ? Object.values(err.errors).flat().join(' ') : (err.message || 'An error occurred.');
            showMessage('error', errorText);
        });
    });
});
</script>

</body>
</html>