@extends('student.layout')

@section('title', 'My Complaints')
@section('page-title', '') {{-- The title is now inside the content --}}

@section('content')
<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-header h2 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }
    .btn-primary {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        text-decoration: none;
        color: #fff;
        background-color: #4e73df;
        border: none;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Complaint Card Styles */
    .complaint-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        border-left: 5px solid #e3e6f0; /* Default border color */
    }
    .complaint-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .complaint-header h5 {
        margin: 0;
        font-weight: 600;
        color: #5a5c69;
    }
    .complaint-body { padding: 1.5rem; }
    .status-badge {
        padding: 0.3em 0.7em;
        font-size: 0.75em;
        font-weight: 700;
        border-radius: 10rem;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-badge.status-pending { background-color: #f6c23e; }
    .status-badge.status-in_progress { background-color: #36b9cc; }
    .status-badge.status-completed { background-color: #1cc88a; }
    
    /* Change card border color based on status */
    .complaint-card.status-pending { border-left-color: #f6c23e; }
    .complaint-card.status-in_progress { border-left-color: #36b9cc; }
    .complaint-card.status-completed { border-left-color: #1cc88a; }

    .admin-reply {
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f8f9fc;
        border-left: 4px solid #4e73df;
        border-radius: 4px;
    }
    .admin-reply p { margin: 0; }
</style>

<div class="page-header">
    <h2>My Complaints</h2>
    <a href="{{ route('student.complaints.create') }}" class="btn btn-primary">Submit a New Complaint</a>
</div>

@forelse($complaints as $complaint)
    @php
        $statusClass = 'status-' . str_replace(' ', '_', strtolower($complaint->status));
    @endphp
    <div class="complaint-card {{ $statusClass }}">
        <div class="complaint-header">
            <h5>Type: {{ $complaint->type }}</h5>
            <span class="status-badge {{ $statusClass }}">{{ $complaint->status }}</span>
        </div>
        <div class="complaint-body">
            <p><strong>Your Message:</strong><br>{{ $complaint->message }}</p>
            <small>Submitted: {{ $complaint->created_at->format('M d, Y H:i A') }}</small>

            @if($complaint->admin_reply)
                <div class="admin-reply">
                    <strong>Response:</strong>
                    <p>{{ $complaint->admin_reply }}</p>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="complaint-card" style="text-align: center; padding: 2rem;">
        <p>You have not submitted any complaints yet.</p>
    </div>
@endforelse
@endsection