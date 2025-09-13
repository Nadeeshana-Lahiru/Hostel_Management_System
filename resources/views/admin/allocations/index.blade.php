@extends('admin.layout')

@section('title', 'Room Allocation')
@section('page-title', '') {{-- The title will be inside the content for better styling --}}

@section('content')
<style>
    /* Page Header */
    .page-header {
        margin-bottom: 20px;
    }
    .page-header h2 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }

    /* Grid Layout for Hostel Cards */
    .hostel-grid {
        display: grid;
        /* Creates responsive columns that are at least 280px wide */
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px; /* Space between cards */
    }

    /* Individual Hostel Card Styling */
    .hostel-card {
        background-color: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column; /* Align content vertically */
        justify-content: space-between;
    }
    .hostel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        border-left: 5px solid #4e73df;
    }
    .hostel-card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .hostel-card-header .icon {
        background-color: #f8f9fc;
        border-radius: 50%;
        padding: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .hostel-card h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #4e73df;
        font-weight: 600;
    }
    .hostel-card p {
        margin: 0;
        font-size: 1rem;
        color: #5a5c69;
        font-weight: 500;
        text-align: right; /* Align room count to the right */
    }
    .no-hostels-message {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        color: #5a5c69;
    }
</style>

<div class="page-header">
    <h2>Room Allocation</h2>
</div>

<div class="hostel-grid">
    @forelse($hostels as $hostel)
        <a href="{{ route('admin.allocations.showHostelRooms', $hostel->id) }}" class="hostel-card">
            <div class="hostel-card-header">
                <div class="icon">
                    {{-- Simple SVG building icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4e73df" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <h3>{{ $hostel->name }}</h3>
            </div>
            <p><strong>Total Rooms:</strong> {{ $hostel->rooms_count }}</p>
        </a>
    @empty
        <div class="no-hostels-message">
            <p>No hostels found. Please add a hostel first to begin allocation.</p>
        </div>
    @endforelse
</div>
@endsection