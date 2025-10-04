@extends('admin.layout')

@section('title', 'All Hostels')
@section('page-title', 'Manage Hostels')

@section('content')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Get the new elements for date and time text.
    const dateText = document.getElementById('date-text');
    const timeText = document.getElementById('time-text');

    // Check if the elements exist on the page.
    if (dateText && timeText) {
        
        function updateClock() {
            const now = new Date();
            
            // Create a more beautiful, readable date format.
            // Example: Saturday, October 4, 2025
            const formattedDate = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Format the time. Example: 3:06:06 PM
            const formattedTime = now.toLocaleTimeString('en-US');

            // Update the text for both elements separately.
            dateText.textContent = formattedDate;
            timeText.textContent = formattedTime;
        }

        // Run once to show the time immediately.
        updateClock();
        
        // Update every second.
        setInterval(updateClock, 1000);
    }
});
</script>
@endpush

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    :root {
        --primary-color: #4e73df;
        --primary-hover: #2e59d9;
        --border-color: #e3e6f0;
        --text-dark: #3a3b45;
        --text-light: #858796;
        --card-shadow: 0 4px 12px rgba(0,0,0,0.08);
        --card-shadow-hover: 0 8px 20px rgba(0,0,0,0.12);
        --card-radius: 12px;
    }

    body {
        background-color: #f8f9fc;
        font-family: 'Nunito', sans-serif;
    }

    .hostel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .hostel-card {
        background-color: #ffffff;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hostel-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow-hover);
    }

    .card-content {
        padding: 25px;
        flex-grow: 1; 
    }

    .card-content h3 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .card-stats {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-light);
        font-size: 0.95rem;
    }

    .stat-item i {
        color: var(--primary-color);
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    .card-footer {
        padding: 15px 25px;
        background-color: #f8f9fc;
        border-top: 1px solid var(--border-color);
        text-align: right;
        font-weight: 600;
        color: var(--primary-color);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .hostel-card:hover .card-footer {
        background-color: var(--primary-color);
        color: #fff;
    }

    .card-footer i {
        margin-left: 5px;
        transition: transform 0.3s ease;
    }

    .hostel-card:hover .card-footer i {
        transform: translateX(5px);
    }

    .empty-state {
        grid-column: 1 / -1; 
        text-align: center;
        padding: 4rem 2rem;
        background-color: #fff;
        border-radius: var(--card-radius);
        border: 2px dashed var(--border-color);
        color: var(--text-light);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        color: var(--text-dark);
        font-weight: 700;
    }

    .btn-add-new {
        display: inline-flex;
        align-items: center;
        gap: 8px; 
        background-color: var(--primary-color);
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
        transition: all 0.3s ease;
    }

    .btn-add-new:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

</style>

<div class="top-controls" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>All Hostels</h2>
    <a href="{{ route('admin.hostels.create') }}" class="btn-add-new"><i class="fas fa-plus"></i> Add New Hostel</a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="hostel-grid">
    @forelse($hostels as $hostel)
        <a href="{{ route('admin.hostels.show', $hostel->id) }}" class="hostel-card">
            <div class="card-content">
                <h3>{{ $hostel->name }}</h3>
                <div class="card-stats">
                    <div class="stat-item">
                        <i class="fas fa-user-shield"></i>
                        <span><strong>Warden:</strong> {{ $hostel->warden->full_name ?? 'Not Assigned' }}</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-door-closed"></i>
                        <span><strong>Total Rooms:</strong> {{ $hostel->rooms_count }}</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span>Manage Hostel <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>
    @empty
        <div class="empty-state">
            <i class="fas fa-hotel"></i>
            <h3>No Hostels Found</h3>
            <p>Get started by adding a new hostel to the system.</p>
        </div>
    @endforelse
</div>
@endsection
