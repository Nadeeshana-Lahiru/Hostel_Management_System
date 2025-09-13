<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel - @yield('title')</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f8f9fa; }
        .wrapper { display: flex; }
        .sidebar { width: 250px; background-color: #343a40; color: white; min-height: 100vh; transition: all 0.3s; }
        .sidebar-header { padding: 20px; text-align: center; background-color: #23272b; }
        .sidebar ul { list-style-type: none; padding: 0; }
        .sidebar ul li a { display: block; padding: 15px 20px; color: #adb5bd; text-decoration: none; transition: all 0.3s; border-left: 3px solid transparent; }
        .sidebar ul li a:hover, .sidebar ul li.active > a { background-color: #495057; color: white; border-left-color: #007bff; }
        .content { width: 100%; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: #fff; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .header-left { font-size: 1.5rem; color: #333; }
        .header-right .logout-form button { background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        .main-content { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Student Panel</h3>
            </div>
            <ul>
                <li class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                <li class="{{ request()->routeIs('student.room.*') ? 'active' : '' }}"><a href="{{ route('student.room.index') }}">My Room</a></li>
                <li class="{{ request()->routeIs('student.complaints.*') ? 'active' : '' }}"><a href="{{ route('student.complaints.index') }}">Complaints</a></li>
                <li class="{{ request()->routeIs('student.feedback.*') ? 'active' : '' }}"><a href="{{ route('student.feedback.index') }}">Feedback</a></li>
                <li class="{{ request()->routeIs('student.settings.*') ? 'active' : '' }}"><a href="{{ route('student.settings.index') }}">Settings</a></li>
            </ul>
        </nav>

        <div class="content">
            <header class="header">
                <div class="header-left">@yield('page-title')</div>
                <div class="header-right">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                    </form>
                </div>
            </header>
            
            <main class="main-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>