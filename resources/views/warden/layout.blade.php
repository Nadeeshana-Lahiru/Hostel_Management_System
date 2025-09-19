<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warden Panel - @yield('title')</title>
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

        /* --- ADD THESE STYLES FOR THE ALERTS --- */
        .alert { padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; position: relative; }
        .alert-success { color: #0f5132; background-color: #d1e7dd; border-color: #badbcc; }
        .alert-danger { color: #842029; background-color: #f8d7da; border-color: #f5c2c7; }
        .alert-dismissible .btn-close { position: absolute; top: 0; right: 0; z-index: 2; padding: 1.25rem 1rem; background: none; border: 0; cursor: pointer; font-size: 1.2rem; font-weight: bold; color: inherit; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Warden Panel</h3>
            </div>
            <ul>
                <li class="{{ request()->routeIs('warden.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('warden.dashboard') }}">Dashboard</a>
                </li>
                <li class="{{ request()->routeIs('warden.hostels.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.hostels.index') }}">Hostels</a>
                </li>
                <li class="{{ request()->routeIs('warden.students.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.students.index') }}">Students</a>
                </li>
                <li class="{{ request()->routeIs('warden.allocations.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.allocations.index') }}">Room Allocation</a>
                </li>
                <li class="{{ request()->routeIs('warden.complaints.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.complaints.index') }}">Complaints</a>
                </li>
                <li class="{{ request()->routeIs('warden.feedback.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.feedback.index') }}">Feedback</a>
                </li>
                <li class="{{ request()->routeIs('warden.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.settings.index') }}">Settings</a>
                </li>
            </ul>
        </nav>

        <div class="content">
            <header class="header">
                <div class="header-left">@yield('page-title')</div>
            </header>
            
            <div class="container-fluid" style="padding: 0;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';" aria-label="Close">×</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';" aria-label="Close">×</button>
                    </div>
                @endif
            </div>
            
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>