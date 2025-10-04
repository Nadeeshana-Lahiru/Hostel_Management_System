<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body { 
            font-family: 'Poppins', 'Segoe UI', sans-serif; 
            margin: 0; 
            background-color: #f4f7fc;
        }
        .wrapper { 
            display: flex; 
        }

        .sidebar {
            width: 260px;
            background-color: #ffffff; 
            color: #333;
            min-height: 100vh;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-right: 1px solid #e0e0e0;
        }
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
        }
        .sidebar-header h3 {
            margin: 0;
            color: #0d6efd; 
            font-weight: 600;
            font-size: 1.5rem;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 15px 0;
            margin: 0;
        }
        .sidebar ul li a {
            display: flex; 
            align-items: center;
            padding: 13px 25px;
            margin: 8px 15px; 
            color: #5b6e88;
            text-decoration: none;
            transition: all 0.3s ease; 
            border-radius: 8px; 
            font-weight: 500;
        }
        .sidebar ul li a .nav-icon {
            margin-right: 15px;
            font-size: 18px;
            width: 20px; 
            text-align: center;
            color: #8a99af; 
            transition: color 0.3s ease;
        }
        .sidebar ul li a:hover {
            background-color: #e9f2ff; 
            color: #0d6efd; 
        }
        .sidebar ul li a:hover .nav-icon {
            color: #0d6efd;
        }
        .sidebar ul li.active > a {
            background-color: #0d6efd; 
            color: white;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3); 
        }
        .sidebar ul li.active > a .nav-icon {
            color: white; 
        }

        .content { width: 100%; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: #fff; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .header-left { font-size: 1.5rem; color: #333; font-weight: 600; }
        .header-right .logout-form button { background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        .main-content { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        .alert { padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; position: relative; }
        .alert-success { color: #0f5132; background-color: #d1e7dd; border-color: #badbcc; }
        .alert-danger { color: #842029; background-color: #f8d7da; border-color: #f5c2c7; }
        .alert-dismissible { padding-right: 3rem; }
        .alert-dismissible .btn-close { position: absolute; top: 0; right: 0; z-index: 2; padding: 1.25rem 1rem; background: none; border: 0; cursor: pointer; font-size: 1.2rem; font-weight: bold; color: inherit; }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            
            <ul>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt nav-icon"></i> Dashboard</a>
                </li>
                <li class="{{ request()->routeIs('admin.wardens.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.wardens.index') }}"><i class="fas fa-user-shield nav-icon"></i> Wardens</a>
                </li>
                <li class="{{ request()->routeIs('admin.hostels.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.hostels.index') }}"><i class="fas fa-building nav-icon"></i> Hostels</a>
                </li>
                <li class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.students.index') }}"><i class="fas fa-user-graduate nav-icon"></i> Students</a>
                </li>
                <li class="{{ request()->routeIs('admin.allocations.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.allocations.index') }}"><i class="fas fa-door-open nav-icon"></i> Room Allocation</a>
                </li>
                <li class="{{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.complaints.index') }}"><i class="fas fa-exclamation-circle nav-icon"></i> Complaints</a>
                </li>
                <li class="{{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.feedback.index') }}"><i class="fas fa-comment-dots nav-icon"></i> Feedback</a>
                </li>
                <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}"><i class="fas fa-cog nav-icon"></i> Settings</a>
                </li>
            </ul>
        </nav>

        <div class="content">
            <header class="header">
                <div class="header-left" style="display: flex; align-items: center; flex-grow: 1;">
        
                    @yield('page-title')
                    
                    <div id="datetime-container" style="display: flex; align-items: center; gap: 15px; margin-left: auto; font-size: 0.9rem; color: #5a5c69;">
                        
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="far fa-calendar-alt"></i>
                            <span id="date-text"></span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; background-color: #f1f3f5; padding: 4px 12px; border-radius: 20px;">
                            <i class="far fa-clock"></i>
                            <span id="time-text"></span>
                        </div>
                    </div>

                </div>
            </header>
            
            <div class="container-fluid">
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
