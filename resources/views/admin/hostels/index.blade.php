@extends('admin.layout')

@section('title', 'All Hostels')
@section('page-title', 'Manage Hostels')

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
        color: #007bff;
    }
    .hostel-card p {
        margin: 5px 0;
        color: #5a5c69;
    }
</style>

<div class="top-controls" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>All Hostels</h2>
    <a href="{{ route('admin.hostels.create') }}" class="btn btn-primary" style="padding: 10px 15px; border-radius: 5px; text-decoration: none; color: white; background-color: #007bff;">Add New Hostel</a>
</div>

@if(session('success'))
    <div style="padding: 10px; margin-bottom: 20px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<div class="hostel-grid">
    @forelse($hostels as $hostel)
        <a href="{{ route('admin.hostels.show', $hostel->id) }}" class="hostel-card">
            <h3>{{ $hostel->name }}</h3>
            <p><strong>Warden:</strong> {{ $hostel->warden->full_name ?? 'Not Assigned' }}</p>
            <p><strong>Total Rooms:</strong> {{ $hostel->rooms_count }}</p>
        </a>
    @empty
        <p>No hostels found. Please add a new hostel.</p>
    @endforelse
</div>
@endsection