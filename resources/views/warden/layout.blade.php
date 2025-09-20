<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warden Panel - @yield('title')</title>

    <!-- === NEW - Font Awesome CDN for icons === -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- === END NEW === -->

    <style>
        /* === MODIFIED - General body and font styles (Same as Admin) === */
        body { 
            font-family: 'Poppins', 'Segoe UI', sans-serif; /* Using a more modern font */
            margin: 0; 
            background-color: #f4f7fc; /* A lighter, softer background color */
        }
        .wrapper { 
            display: flex; 
        }
        /* === END MODIFIED === */


        /* === NEW STYLES FOR THE SIDEBAR - Copied from Admin Panel for consistency === */
        .sidebar {
            width: 260px;
            background-color: #ffffff; /* Changed to white background */
            color: #333;
            min-height: 100vh;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Soft shadow for depth */
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
            color: #0d6efd; /* Blue color for the header text */
            font-weight: 600;
            font-size: 1.5rem;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 15px 0;
            margin: 0;
        }
        .sidebar ul li a {
            display: flex; /* Using flexbox to align icon and text */
            align-items: center;
            padding: 13px 25px;
            margin: 8px 15px; /* Spacing between menu items */
            color: #5b6e88;
            text-decoration: none;
            transition: all 0.3s ease; /* Smooth transition for hover and active states */
            border-radius: 8px; /* Rounded corners for menu items */
            font-weight: 500;
        }
        .sidebar ul li a .nav-icon {
            margin-right: 15px;
            font-size: 18px;
            width: 20px; /* Fixed width for icon alignment */
            text-align: center;
            color: #8a99af; /* A slightly muted icon color */
            transition: color 0.3s ease;
        }
        .sidebar ul li a:hover {
            background-color: #e9f2ff; /* Light blue background on hover */
            color: #0d6efd; /* Blue text on hover */
        }
        .sidebar ul li a:hover .nav-icon {
            color: #0d6efd; /* Blue icon on hover */
        }
        .sidebar ul li.active > a {
            background-color: #0d6efd; /* Solid blue background for active link */
            color: white;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3); /* Shadow for active link */
        }
        .sidebar ul li.active > a .nav-icon {
            color: white; /* White icon for active link */
        }
        /* === END OF NEW SIDEBAR STYLES === */


        /* Unchanged styles for the main content area */
        .content { width: 100%; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: #fff; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .header-left { font-size: 1.5rem; color: #333; font-weight: 600; }
        .header-right .logout-form button { background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        .main-content { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        /* Styles for alert messages */
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
                <!-- === MODIFIED - Changed Title to Warden Panel === -->
                <h3>Warden Panel</h3>
            </div>
            
            <!-- === MODIFIED - Added icons to each navigation link === -->
            <ul>
                <li class="{{ request()->routeIs('warden.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('warden.dashboard') }}"><i class="fas fa-tachometer-alt nav-icon"></i> Dashboard</a>
                </li>
                <li class="{{ request()->routeIs('warden.hostels.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.hostels.index') }}"><i class="fas fa-building nav-icon"></i> Hostels</a>
                </li>
                <li class="{{ request()->routeIs('warden.students.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.students.index') }}"><i class="fas fa-user-graduate nav-icon"></i> Students</a>
                </li>
                <li class="{{ request()->routeIs('warden.allocations.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.allocations.index') }}"><i class="fas fa-door-open nav-icon"></i> Room Allocation</a>
                </li>
                <li class="{{ request()->routeIs('warden.complaints.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.complaints.index') }}"><i class="fas fa-exclamation-circle nav-icon"></i> Complaints</a>
                </li>
                <li class="{{ request()->routeIs('warden.feedback.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.feedback.index') }}"><i class="fas fa-comment-dots nav-icon"></i> Feedback</a>
                </li>
                <li class="{{ request()->routeIs('warden.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('warden.settings.index') }}"><i class="fas fa-cog nav-icon"></i> Settings</a>
                </li>
            </ul>
             <!-- === END MODIFIED === -->
        </nav>

        <div class="content">
            <header class="header">
                <div class="header-left">
                    @yield('page-title')
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
