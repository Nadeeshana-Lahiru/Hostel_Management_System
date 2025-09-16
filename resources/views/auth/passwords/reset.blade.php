<!DOCTYPE html
><html>
    <head><title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
            box-sizing: border-box;
        }
        .main-title {
            font-size: 2.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center; /* This helps align cards if they have different heights */
            gap: 2rem;
            width: 100%;
            max-width: 1200px;
            /* The 'flex-wrap' property has been removed to force a single line */
        }
        .login-card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 350px;
            border-top: 4px solid #ccc;
            transition: all 0.3s ease-in-out;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }
        .card-header .icon {
            padding: 10px;
            border-radius: 50%;
            display: inline-flex;
        }
        .card-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: all 0.2s;
        }
        .btn-login {
            width: 100%; padding: 12px; border: none; border-radius: 8px;
            font-size: 1rem; font-weight: 600; color: white; cursor: pointer;
            transition: background-color 0.3s;
        }
        .forgot-password { text-align: right; margin-top: 0.75rem; }
        .forgot-password a { text-decoration: none; font-size: 0.9rem; }

        /* --- THEME COLORS --- */
        .admin-theme { border-top-color: #4e73df; }
        .admin-theme .icon { background-color: #eaecf4; }
        .admin-theme h3 { color: #4e73df; }
        .admin-theme .btn-login { background-color: #4e73df; }
        .admin-theme input:focus { border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2); }
        .admin-theme .forgot-password a { color: #4e73df; }

        .warden-theme { border-top-color: #6610f2; }
        .warden-theme .icon { background-color: #e5d9fa; }
        .warden-theme h3 { color: #6610f2; }
        .warden-theme .btn-login { background-color: #6610f2; }
        .warden-theme input:focus { border-color: #6610f2; box-shadow: 0 0 0 3px rgba(102, 16, 242, 0.2); }
        .warden-theme .forgot-password a { color: #6610f2; }

        .student-theme { border-top-color: #1cc88a; }
        .student-theme .icon { background-color: #d1fae5; }
        .student-theme h3 { color: #1cc88a; }
        .student-theme .btn-login { background-color: #1cc88a; }
        .student-theme input:focus { border-color: #1cc88a; box-shadow: 0 0 0 3px rgba(28, 200, 138, 0.2); }
        .student-theme .forgot-password a { color: #1cc88a; }

        .error-message { 
            color: #dc3545; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 1rem; 
            text-align: center; 
        }

        /* For smaller screens, stack the cards vertically */
        @media (max-width: 1150px) {
            .login-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container" style="max-width: 450px;">
        <h2>Enter New Password</h2>
        <p>An OTP has been sent. Please enter it below along with your new password.</p>

        @if(session('error')) <div class="error-message">{{ session('error') }}</div> @endif
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="form-group"><label for="otp">6-Digit OTP</label><input id="otp" type="text" name="otp" required></div>
            <div class="form-group"><label for="password">New Password</label><input id="password" type="password" name="password" required></div>
            <div class="form-group"><label for="password-confirm">Confirm New Password</label><input id="password-confirm" type="password" name="password_confirmation" required></div>
            
            <button type="submit" class="btn-login">Change Password</button>
        </form>
    </div>
</body></html>