<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Login</title>
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
    <h1 class="main-title">Login to Your Hostel</h1>

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
                <div class="forgot-password"><a href="#">Forgot Password?</a></div>
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
                <div class="forgot-password"><a href="#">Forgot Password?</a></div>
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
                <div class="forgot-password"><a href="#">Forgot Password?</a></div>
            </form>
        </div>
    </div>
</body>
</html>