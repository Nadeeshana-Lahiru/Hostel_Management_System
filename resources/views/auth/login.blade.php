<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Login</title>
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --light-gray: #f8f9fc;
            --medium-gray: #e3e6f0;
            --dark-gray: #858796;
            --text-color: #5a5c69;
            --success-color: #1cc88a;
            --modal-background: #ffffff;
            --text-primary: #333;
            --text-secondary: #6c757d;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef2f7;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 950px;
            margin: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12); 
            overflow: hidden;
        }
        .login-image-panel {
            flex-basis: 50%;
            position: relative;
            background-color: #f8f9fc;
        }
        .slideshow-image {
            position: absolute; 
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out; 
        }
        .slideshow-image.active {
            opacity: 1; 
        }
        .login-image-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .login-form-panel {
            flex-basis: 50%;
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
            width: 40%;
            margin: 0 auto;
            display: block;
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
        .btn-login:active { transform: scale(0.98); }
        .forgot-password { text-align: right; margin-top: 1rem; }
        .forgot-password-btn {
            background: none; border: none; padding: 0; font-family: inherit; font-size: 0.9rem;
            cursor: pointer; text-decoration: none; font-weight: 500; color: #4e73df;
        }

        .inline-error-msg {
            color: var(--danger-color); font-size: 0.85rem; font-weight: 500;
            margin-top: 8px; display: none;
        }

        input.is-invalid { border-color: var(--danger-color); 
        }
        
        input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(231, 74, 59, 0.2);
        }
        .main-title {
            font-size: 2.2rem; font-weight: 600; color: #3a3b45;
            margin-bottom: 2rem; text-align: center;
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

        /* @media (max-width: 768px) {
            .login-image-panel { display: none; }
            .login-form-panel { flex-basis: 100%; padding: 2rem; }
            .login-wrapper { margin: 10px; flex-direction: column; }
            .error-message { position: relative; top: 0; left: 0; transform: none; margin-bottom: 1rem; }
        } */

            @media (max-width: 768px) {
            .login-image-panel { display: none; }
            .login-form-panel { flex-basis: 100%; padding: 2rem; }
            .login-wrapper { margin: 10px; flex-direction: column; }
        }

        .main-title {
            font-size: 2.2rem;
            font-weight: 600;
            color: #3a3b45;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* --- CHANGE: PUZZLE STYLES REFINED --- */
        #puzzle_container {
            position: relative; height: 250px; background-color: var(--light-gray);
            border-radius: 8px; overflow: hidden; user-select: none; margin-top: 1.5rem;
        }
        .puzzle-piece, .puzzle-target {
            position: absolute; width: 60px; height: 60px;
            display: flex; align-items: center; justify-content: center; font-size: 2.5rem;
        }
        .puzzle-piece {
            cursor: grab; background-color: rgba(255,255,255,0.7);
            border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .puzzle-piece:active { cursor: grabbing; box-shadow: 0 6px 15px rgba(0,0,0,0.2); }
        .puzzle-target {
            border: 3px dashed var(--dark-gray); border-radius: 12px;
            color: var(--dark-gray); opacity: 0.5;
        }
        #puzzle_error_msg {
            color: var(--danger-color); font-weight: 500;
            min-height: 1.2rem; /* Use min-height to prevent layout shift */
            margin-bottom: 1rem; /* Changed from margin-top */
            text-align: center; opacity: 0;
            transition: opacity 0.3s;
        }

        /* Robot Checkbox Styles */
        #robot_check_container {
            margin-top: 1.5rem; display: none;
            opacity: 0; transition: opacity 0.5s ease-in-out;
        }
        .robot-check {
            display: flex; align-items: center; padding: 15px;
            border: 1px solid var(--medium-gray); border-radius: 8px;
            background-color: var(--light-gray); cursor: pointer;
        }
        .robot-check:hover { border-color: #c5cbe0; }
        .robot-check input[type="checkbox"] {
            width: 20px; height: 20px; margin-right: 15px; cursor: pointer;
        }
        .robot-check label { margin: 0; font-size: 1rem; color: var(--text-color); cursor: pointer; }

        /* --- CHANGE: SHAKE ANIMATION FOR PUZZLE ERROR --- */
        @keyframes shake {
            10%, 90% { transform: translateX(-1px); }
            20%, 80% { transform: translateX(2px); }
            30%, 50%, 70% { transform: translateX(-4px); }
            40%, 60% { transform: translateX(4px); }
        }
        .shake { animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both; }
        
        /* --- CHANGE: SUCCESS CHECKMARK ANIMATION --- */

        /* Styles the modal's appearance and layout.
        /* ------------------------------------------ */
        #successModal .modal-content {
            max-width: 360px;
            padding: 20px 30px 25px 30px;
            text-align: center;
            background-color: var(--modal-background);
            border-radius: 12px;
            /* Animation for the modal pop-up effect */
            animation: scaleIn 0.3s ease-out;
        }

        #successModal .modal-body {
            padding: 0;
        }

        #successModal h3 {
            margin: 0.5rem 0;
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
        }

        #successModal p {
            margin: 0 0 1.75rem 0;
            color: var(--text-secondary);
            font-size: 1rem;
        }

        #successModal .modal-buttons {
            grid-template-columns: 1fr;
            justify-items: center;
            margin-top: 0;
        }

        #successModal #finalLoginBtn {
            max-width: 150px;
        }


        /* 3. Animated Checkmark
        /* All styles required for the checkmark animation.
        /* ------------------------------------------ */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }

        .success-checkmark .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            display: block;
            box-sizing: content-box;
            border: 4px solid var(--success-color);
            border-radius: 50%;
        }

        /* Pseudo-elements used to create the circle-wipe effect */
        .success-checkmark .check-icon::before,
        .success-checkmark .check-icon::after {
            content: '';
            height: 100px;
            position: absolute;
            background: var(--modal-background);
            transform: rotate(-45deg);
        }

        .success-checkmark .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }

        .success-checkmark .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            /* This animation rotates the element to reveal the checkmark */
            animation: rotate-circle 0.8s 0.3s ease-in forwards;
        }

        /* The two lines that form the checkmark */
        .success-checkmark .icon-line {
            height: 5px;
            background-color: var(--success-color);
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .success-checkmark .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            /* Animation to draw the shorter line with a rebound effect */
            animation: icon-line-tip 0.75s forwards;
        }

        .success-checkmark .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            /* Animation to draw the longer line with a rebound effect */
            animation: icon-line-long 0.75s forwards;
        }

        /* This element hides part of the checkmark lines during animation */
        .success-checkmark .icon-fix {
            position: absolute;
            z-index: 1;
            width: 5px;
            height: 90px;
            top: 8px;
            left: 28px;
            background-color: var(--modal-background);
            transform: rotate(-45deg);
        }


        /* 4. Animation Keyframes
        /* Defines the steps for all animations.
        /* ------------------------------------------ */

        /* Modal pop-in effect */
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Circle-wipe effect */
        @keyframes rotate-circle {
            from {
                transform: rotate(-45deg);
            }
            to {
                transform: rotate(-405deg);
            }
        }

        /* Draws the shorter checkmark line */
        @keyframes icon-line-tip {
            0%   { width: 0; left: 1px; top: 19px; }
            54%  { width: 0; left: 1px; top: 19px; }
            70%  { width: 50px; left: -8px; top: 37px; }
            84%  { width: 17px; left: 21px; top: 48px; }
            100% { width: 25px; left: 14px; top: 46px; }
        }

        /* Draws the longer checkmark line */
        @keyframes icon-line-long {
            0%   { width: 0; right: 46px; top: 54px; }
            65%  { width: 0; right: 46px; top: 54px; }
            84%  { width: 55px; right: 0px; top: 35px; }
            100% { width: 47px; right: 8px; top: 38px; }
        }
        
        
    </style>
</head>
<body>
    <h1 class="main-title">Hostel Management SEUSL</h1>

    <div class="login-wrapper">
        <div class="login-image-panel">
            <img src="{{ asset('images/hostel.jpg') }}" alt="Hostel view" class="slideshow-image active">
            <img src="{{ asset('images/hostel2.jpg') }}" alt="Hostel view" class="slideshow-image">
            <img src="{{ asset('images/hostel3.jpg') }}" alt="Hostel view" class="slideshow-image">
        </div>
        <div class="login-form-panel">
            <h2>Welcome Back!</h2>
            <p>Please enter your credentials to log in.</p>
            
            <form id="mainLoginForm" action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Email or Registration Number</label>
                    <input type="text" id="username" name="username" placeholder="Enter your email or reg no" required>
                    <div class="inline-error-msg" id="username_error"></div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <div class="inline-error-msg" id="password_error"></div>
                </div>
                
                <button type="button" id="checkCredentialsBtn" class="btn-login">Login</button>
                
                <div class="forgot-password">
                    <button type="button" class="forgot-password-btn">Forgot Password?</button>
                </div>

                <div id="robot_check_container">
                    <div class="robot-check">
                        <input type="checkbox" id="robot_check_box">
                        <label for="robot_check_box">I am not a robot</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- PUZZLE MODAL -->
    <div id="puzzleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Complete the Security Puzzle</h3>
            </div>
            <div class="modal-body">
                <div id="puzzle_error_msg"></div>
                <p>Drag the item on the left to its matching home on the right.</p>
                <div id="puzzle_container">
                    <div id="puzzle_piece" class="puzzle-piece"></div>
                    <div id="puzzle_target" class="puzzle-target"></div>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" id="puzzleCancelBtn" class="btn btn-secondary">Cancel</button>
                <button type="button" id="puzzleSubmitBtn" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

    <!-- SUCCESS MODAL -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-body">
                {{-- CHANGE: Added animated checkmark HTML --}}
                <div class="success-checkmark">
                    <div class="check-icon">
                      <span class="icon-line line-tip"></span>
                      <span class="icon-line line-long"></span>
                      <div class="icon-circle"></div>
                      <div class="icon-fix"></div>
                    </div>
                </div>
                <h3 style="margin-top:1rem; font-size: 1.5rem;">Login Successful!</h3>
                <p>You will now be redirected to your dashboard.</p>
            </div>
            <div class="modal-buttons" style="grid-template-columns: 1fr; justify-items: center;">
                <button type="button" id="finalLoginBtn" class="btn btn-primary" style="max-width: 150px;">OK</button>
            </div>
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

    // --- JAVASCRIPT CHANGE: Add this new script for the slideshow ---
    const slideshowImages = document.querySelectorAll(".slideshow-image");
    let currentImageIndex = 0;

    if (slideshowImages.length > 0) {
        setInterval(() => {
            // Hide the current image
            slideshowImages[currentImageIndex].classList.remove("active");

            // Calculate the index of the next image
            currentImageIndex = (currentImageIndex + 1) % slideshowImages.length;

            // Show the next image
            slideshowImages[currentImageIndex].classList.add("active");
        }, 5000); // 5000 milliseconds = 5 seconds
    }

    // =============================================================
    // --- UPDATED SCRIPT FOR CUSTOM LOGIN FLOW ---
    // =============================================================

    const mainLoginForm = document.getElementById('mainLoginForm');
    const checkCredentialsBtn = document.getElementById('checkCredentialsBtn');
    
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const usernameError = document.getElementById('username_error');
    const passwordError = document.getElementById('password_error');

    const robotCheckContainer = document.getElementById('robot_check_container');
    const robotCheckBox = document.getElementById('robot_check_box');

    const puzzleModal = document.getElementById('puzzleModal');
    const successModal = document.getElementById('successModal');
    
    function clearErrors() {
        usernameError.style.display = 'none';
        usernameInput.classList.remove('is-invalid');
        passwordError.style.display = 'none';
        passwordInput.classList.remove('is-invalid');
    }

    checkCredentialsBtn.addEventListener('click', async () => {
        clearErrors();
        robotCheckContainer.style.opacity = '0';
        robotCheckContainer.style.display = 'none';
        robotCheckBox.checked = false;

        const formData = new FormData(mainLoginForm);
        
        try {
            const response = await fetch('{{ route("login.check") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.username) {
                        usernameInput.classList.add('is-invalid');
                        usernameError.textContent = data.errors.username[0];
                        usernameError.style.display = 'block';
                    }
                    if (data.errors.password) {
                        passwordInput.classList.add('is-invalid');
                        passwordError.textContent = data.errors.password[0];
                        passwordError.style.display = 'block';
                    }
                }
            } else {
                robotCheckContainer.style.display = 'block';
                setTimeout(() => { robotCheckContainer.style.opacity = '1'; }, 50);
            }
        } catch (error) {
            console.error('An error occurred:', error);
            passwordError.textContent = 'An unexpected error occurred. Please try again.';
            passwordError.style.display = 'block';
        }
    });

    robotCheckBox.addEventListener('change', () => {
        if (robotCheckBox.checked) {
            setupAndShowPuzzle();
        }
    });

    // --- CHANGE: Puzzle logic updated for regeneration ---
    const puzzleContainer = document.getElementById('puzzle_container');
    const piece = document.getElementById('puzzle_piece');
    const target = document.getElementById('puzzle_target');
    const puzzleErrorMsg = document.getElementById('puzzle_error_msg');
    const puzzleCancelBtn = document.getElementById('puzzleCancelBtn');
    const puzzleSubmitBtn = document.getElementById('puzzleSubmitBtn');
    const puzzleModalContent = puzzleModal.querySelector('.modal-content');

    // CHANGE: Create a pool of puzzles to choose from
    const puzzles = [
        { piece: '🔑', target: '🔒' },
        { piece: '✉️', target: '📫' },
        { piece: '🚗', target: '🛖' },
        { piece: '🧀', target: '🐭' },
        { piece: '🚀', target: '🪐' }
    ];
    let currentPuzzleIndex = -1;

    let isDragging = false;
    let offsetX, offsetY;

    function isSolved() {
        const pieceRect = piece.getBoundingClientRect();
        const targetRect = target.getBoundingClientRect();
        const overlapThreshold = 0.6; // Piece must overlap 60% of the target
        
        const xOverlap = Math.max(0, Math.min(pieceRect.right, targetRect.right) - Math.max(pieceRect.left, targetRect.left));
        const yOverlap = Math.max(0, Math.min(pieceRect.bottom, targetRect.bottom) - Math.max(pieceRect.top, targetRect.top));
        const overlapArea = xOverlap * yOverlap;
        const targetArea = targetRect.width * targetRect.height;

        return overlapArea / targetArea > overlapThreshold;
    }

    function randomizePositions() {
        const containerWidth = puzzleContainer.clientWidth;
        const containerHeight = puzzleContainer.clientHeight;
        const pieceSize = 60;
        const padding = 10;

        target.style.top = `${Math.random() * (containerHeight - pieceSize)}px`;
        target.style.left = `${Math.random() * (containerWidth - pieceSize)}px`;

        let pieceX, pieceY;
        do {
            pieceX = Math.random() * (containerWidth - pieceSize);
            pieceY = Math.random() * (containerHeight - pieceSize);
        } while (
            Math.abs(pieceX - parseFloat(target.style.left)) < (pieceSize * 1.5) && 
            Math.abs(pieceY - parseFloat(target.style.top)) < (pieceSize * 1.5)
        );
        piece.style.top = `${pieceY}px`;
        piece.style.left = `${pieceX}px`;
    }
    
    function generateNewPuzzle() {
        // CHANGE: Select a new random puzzle that is different from the current one
        let newIndex;
        do {
            newIndex = Math.floor(Math.random() * puzzles.length);
        } while (newIndex === currentPuzzleIndex);
        currentPuzzleIndex = newIndex;
        
        const newPuzzle = puzzles[currentPuzzleIndex];
        piece.innerHTML = newPuzzle.piece;
        target.innerHTML = newPuzzle.target;
        
        randomizePositions();
    }

    function setupAndShowPuzzle() {
        puzzleModal.style.display = 'flex';
        puzzleErrorMsg.textContent = '';
        puzzleErrorMsg.style.opacity = '0';
        generateNewPuzzle();
    }

    puzzleCancelBtn.addEventListener('click', () => {
        puzzleModal.style.display = 'none';
        robotCheckBox.checked = false;
    });

    puzzleSubmitBtn.addEventListener('click', () => {
        if (isSolved()) {
            puzzleModal.style.display = 'none';
            successModal.style.display = 'flex';
        } else {
            // CHANGE: Add shake animation and regenerate a completely new puzzle
            puzzleModalContent.classList.add('shake');
            puzzleErrorMsg.textContent = 'Puzzle not correct. Try this new one!';
            puzzleErrorMsg.style.opacity = '1';
            generateNewPuzzle();
            
            // Remove shake class after animation ends
            setTimeout(() => {
                puzzleModalContent.classList.remove('shake');
            }, 820);
        }
    });

    piece.addEventListener('mousedown', (e) => {
        isDragging = true;
        offsetX = e.clientX - piece.getBoundingClientRect().left;
        offsetY = e.clientY - piece.getBoundingClientRect().top;
        piece.style.transition = 'none';
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        let x = e.clientX - puzzleContainer.getBoundingClientRect().left - offsetX;
        let y = e.clientY - puzzleContainer.getBoundingClientRect().top - offsetY;

        x = Math.max(0, Math.min(x, puzzleContainer.clientWidth - piece.clientWidth));
        y = Math.max(0, Math.min(y, puzzleContainer.clientHeight - piece.clientHeight));
        
        piece.style.left = `${x}px`;
        piece.style.top = `${y}px`;
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        piece.style.transition = '';
    });
    
    const finalLoginBtn = document.getElementById('finalLoginBtn');
    finalLoginBtn.addEventListener('click', () => {
        mainLoginForm.submit();
    });
});
</script>

</body>
</html>