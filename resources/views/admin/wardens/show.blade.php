@extends('admin.layout')

@section('title', 'Warden Details')
@section('page-title', '')

@section('content')
<style>
    .details-container { max-width: 900px; margin: auto; }
    .details-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e3e6f0; }
    .details-header h2 { margin: 0; font-size: 1.75rem; color: #333; }
    .details-header .actions { display: flex; gap: 10px; }
    .btn { padding: 0.6rem 1.2rem; font-weight: 600; font-size: 0.9rem; text-align: center; text-decoration: none; color: white; border: none; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .btn-secondary { background-color: #858796; }
    .btn-warning { background-color: #f6c23e; }

    .details-card { background-color: #fff; padding: 1.5rem 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .details-card h5 { font-weight: 600; font-size: 1.2rem; color: #4e73df; margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid #e3e6f0; padding-bottom: 0.75rem; }
    .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 2rem; }
    .detail-item { margin-bottom: 0.5rem; }
    .detail-item strong { display: block; color: #858796; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 2px; }
    .detail-item span { font-size: 1rem; color: #5a5c69; }
</style>

<div class="details-container">
    <div class="details-header">
        <h2>{{ $warden->full_name }}</h2>
        <div class="actions">
            <a href="{{ route('admin.wardens.index') }}" class="btn btn-secondary">&larr; Back to List</a>
            <a href="{{ route('admin.wardens.edit', $warden->id) }}" class="btn btn-warning">Edit Warden</a>
        </div>
    </div>

    <div class="details-card">
        <h5>Warden Information</h5>
        <div class="details-grid">
            <div class="detail-item"><strong>Full Name</strong><span>{{ $warden->full_name }}</span></div>
            <div class="detail-item"><strong>Name with Initials</strong><span>{{ $warden->initial_name }}</span></div>
            <div class="detail-item"><strong>NIC</strong><span>{{ $warden->nic }}</span></div>
            <div class="detail-item"><strong>Email Address</strong><span>{{ $warden->user->email }}</span></div>
            <div class="detail-item"><strong>Date of Birth</strong><span>{{ $warden->dob }}</span></div>
            <div class="detail-item"><strong>Gender</strong><span>{{ ucfirst($warden->gender) }}</span></div>
            <div class="detail-item"><strong>Telephone</strong><span>{{ $warden->telephone_number }}</span></div>
            <div class="detail-item"><strong>Civil Status</strong><span>{{ ucfirst($warden->civil_status) }}</span></div>
            <div class="detail-item"><strong>Address</strong><span>{{ $warden->address }}</span></div>
            <div class="detail-item"><strong>District</strong><span>{{ $warden->district }}</span></div>
            <div class="detail-item"><strong>Province</strong><span>{{ $warden->province }}</span></div>
            <div class="detail-item"><strong>Nationality</strong><span>{{ $warden->nationality }}</span></div>
        </div>
    </div>
</div>
@endsection