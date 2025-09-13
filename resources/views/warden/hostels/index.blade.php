@extends('warden.layout')

@section('title', 'My Hostel')
@section('page-title', 'My Assigned Hostel')

@section('content')
<style>
    .hostel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    .hostel-card {
        background-color: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .hostel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .hostel-card h3 {
        margin-top: 0;
        color: #4e73df;
    }
    .hostel-card p {
        margin: 5px 0;
        color: #5a5c69;
    }
</style>

{{-- Note: The "Add New Hostel" button is removed for the warden view --}}

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="hostel-grid">
    @forelse($hostels as $hostel)
        {{-- The route is now 'warden.hostels.show' --}}
        <a href="{{ route('warden.hostels.show', $hostel->id) }}" class="hostel-card">
            <h3>{{ $hostel->name }}</h3>
            <p><strong>Total Rooms:</strong> {{ $hostel->rooms_count }}</p>
        </a>
    @empty
        <p>You have not been assigned to a hostel yet. Please contact the administrator.</p>
    @endforelse
</div>
@endsection