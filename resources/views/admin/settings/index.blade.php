@extends('admin.layout')
@section('title', 'Settings')
@section('page-title', 'Account Settings')

@section('content')
<style>
    /* Main Page Styles */
    .settings-card { background-color: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 600px; margin: auto; }
    .settings-section { border-bottom: 1px solid #e3e6f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
    .settings-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .settings-section h5 { font-size: 1.2rem; font-weight: 600; color: #5a5c69; margin-bottom: 1rem; }
    .account-email { font-size: 1rem; color: #333; background: #f8f9fc; padding: 0.75rem; border-radius: 5px; border: 1px solid #e3e6f0; }
    
    /* General Button Styles */
    .btn {
        width: 50%;
        padding: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease-in-out;
        margin-left: 25%;;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn-warning { background-color: #f6c23e; color: #fff; }
    .btn-danger { background-color: #e74a3b; color: #fff; }
    .btn-primary { background-color: #4e73df; color: #fff; }
    .btn-submit { background-color: #1cc88a; color: #fff; }

    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 25px; border: 1px solid #888; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: scale(0.95);} to {opacity: 1; transform: scale(1);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; color: #333; }
    .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-step { display: none; }
    .modal-step.active { display: block; }

    /* Form Styles (Inside Modal) */
    .modal .form-group label { font-weight: 600; margin-bottom: 0.5rem; color: #5a5c69; display: block; }
    .modal .form-group input {
        width: 100%;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #d1d3e2;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .modal .form-group input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    #modal-error { 
        padding: 0.75rem;
        margin-top: 1rem;
        border-radius: 5px;
        background-color: #fee2e2; 
        color: #991b1b; 
        border: 1px solid #fecaca;
        font-weight: 500;
        display: none; 
    }
</style>

<div class="settings-card">
    <div class="settings-section">
        <h5>Account Details</h5>
        <p class="account-email"><strong>Email:</strong> {{ Auth::user()->email }}</p>
    </div>

    <div class="settings-section">
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

        <div id="step1" class="modal-step active">
            <p>Enter your account email to receive a verification OTP.</p>
            <form id="sendOtpForm">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Send OTP</button>
            </form>
        </div>

        <div id="step2" class="modal-step">
            <p>An OTP has been sent. Please enter it below.</p>
            <form id="verifyOtpForm">
                @csrf
                <div class="form-group">
                    <label for="otp">6-Digit OTP</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Confirm</button>
            </form>
        </div>

        <div id="step3" class="modal-step">
            <p>OTP verified! You can now set a new password.</p>
            <form id="changePasswordForm" action="{{ route('admin.settings.changePassword') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-submit" style="margin-top: 1rem; width: 100%;">Change Password</button>
            </form>
        </div>
        <div id="modal-error"></div>
    </div>
</div>

<script>
    // Your Javascript for the modal remains the same
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('passwordModal');
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        const closeBtn = document.querySelector('.close-button');
        const modalError = document.getElementById('modal-error');

        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');

        changePasswordBtn.onclick = function() { modal.style.display = 'block'; }
        closeBtn.onclick = function() { modal.style.display = 'none'; resetModal(); }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                resetModal();
            }
        }
        
        function resetModal() { /* ... reset logic ... */ }
        function showError(message) { /* ... error logic ... */ }
    // Handle Step 1: Send OTP
    document.getElementById('sendOtpForm').addEventListener('submit', function (e) {
        e.preventDefault();
        // UPDATED FETCH URL
        fetch('{{ route("admin.settings.sendOtp") }}', { /* ... */ });
    });

    // Handle Step 2: Verify OTP
    document.getElementById('verifyOtpForm').addEventListener('submit', function (e) {
        e.preventDefault();
        // UPDATED FETCH URL
        fetch('{{ route("admin.settings.verifyOtp") }}', { /* ... */ });
    });
    });
</script>
@endsection