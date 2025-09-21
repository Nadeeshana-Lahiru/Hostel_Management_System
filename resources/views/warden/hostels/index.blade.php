@extends('warden.layout')

@section('title', 'My Hostel')
@section('page-title', 'My Assigned Hostel')

@section('content')
{{-- Added Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="hostel-grid">
    @forelse($hostels as $hostel)
        {{-- MODIFICATION: The entire card is still a link, but the internal structure is new --}}
        <a href="{{ route('warden.hostels.show', $hostel->id) }}" class="hostel-card">
            <div class="card-content">
                <h3>{{ $hostel->name }}</h3>
                <div class="card-stats">
                    <div class="stat-item">
                        <i class="fas fa-door-closed"></i>
                        <span><strong>{{ $hostel->rooms_count }}</strong> Total Rooms</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span>View Details <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>
    @empty
        {{-- MODIFICATION: Improved styling for the empty state message --}}
        <div class="empty-state">
            <i class="fas fa-home"></i>
            <h3>No Hostel Assigned</h3>
            <p>You have not been assigned to a hostel yet. Please contact the administrator.</p>
        </div>
    @endforelse
</div>
@endsection

@push('styles')
<style>
    /* --- MODIFIED: Complete CSS Overhaul for a "Beautiful" Grid --- */

    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased; /* Improves font rendering */
        -moz-osx-font-smoothing: grayscale;  /* Improves font rendering */
        background-color: #f8f9fc; /* A light background for the whole page */
    }
    /* Define theme variables */
    :root {
        --primary-color: #4e73df;
        --primary-hover: #2e59d9;
        --border-color: #e3e6f0;
        --card-shadow: 0 4px 12px rgba(0,0,0,0.08);
        --card-shadow-hover: 0 8px 20px rgba(0,0,0,0.12);
        --card-radius: 12px;
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
        display: flex; /* Use flexbox for a flexible layout */
        flex-direction: column;
        overflow: hidden; /* Important for keeping image corners rounded */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hostel-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow-hover);
    }

    /* Card Image styling */
    .card-image {
        height: 180px;
        overflow: hidden;
    }
    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures the image covers the area without distortion */
        transition: transform 0.4s ease;
    }
    .hostel-card:hover .card-image img {
        transform: scale(1.05); /* Subtle zoom effect on hover */
    }

    /* Card Content styling */
    .card-content {
        padding: 20px;
        flex-grow: 1; /* Allows this section to grow, pushing the footer down */
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
        gap: 10px;
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

    /* Card Footer (Call to Action) */
    .card-footer {
        padding: 15px 20px;
        background-color: #f8f9fc;
        border-top: 1px solid var(--border-color);
        text-align: right;
        font-weight: 600;
        color: var(--primary-color);
        transition: background-color 0.3s ease;
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

    /* Styling for the empty state message */
    .empty-state {
        grid-column: 1 / -1; /* Make it span the full grid width */
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