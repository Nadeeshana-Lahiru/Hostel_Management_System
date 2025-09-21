@extends('student.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-grid">
    
    {{-- MODIFICATION: Added a new class "details-card" to apply the color scheme --}}
    <a href="{{ route('student.room.index') }}" class="dashboard-card details-card">
        <div class="card-header">
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bed"><path d="M2 3v16h20V3a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path><path d="M2 12h20"></path><path d="M4 18v-6"></path><path d="M20 18v-6"></path></svg>
            </div>
            <h3>My Room Details</h3>
        </div>
        <p>View your room and roommate information.</p>
    </a>

    <div class="message-center">
        <h4>Announcements & Notices</h4>
        <div class="message-history">
            @forelse($messages as $message)
                <div class="message-item">
                    <div class="message-header">
                        <strong>{{ $message->title }}</strong>
                        <small>From: {{ ucfirst($message->sender_role) }}</small>
                    </div>
                    <p>{{ $message->body }}</p>
                    <small class="message-footer">{{ $message->created_at->format('M d, Y H:i A') }}</small>
                </div>
            @empty
                <p style="text-align: center; color: #858796; padding-top: 20px;">No messages have been posted.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Define consistent theme variables */
    :root {
        --primary-color: #4e73df;
        --border-color: #e3e6f0;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --card-radius: 12px;
        --text-dark: #3a3b45;
        --text-light: #5a5c69;
    }

    /* --- MODIFIED: Grid layout --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr; /* Default to a single column for mobile */
        gap: 2rem;
        align-items: start;
    }

    /* On larger screens (laptops, etc.), create a two-column layout */
    /* The 2nd column (message center) will be 1.5 times wider */
    @media (min-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 1fr 1.5fr;
        }
    }

    /* --- MODIFIED: Base style for the dashboard card --- */
    .dashboard-card {
        background-color: #ffffff;
        border-radius: var(--card-radius);
        padding: 25px;
        box-shadow: var(--card-shadow);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        border-left: 5px solid; /* The color will be set by the variant class */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .dashboard-card .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .dashboard-card .icon-container {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .dashboard-card h3 {
        margin: 0;
        font-size: 1.25rem;
        color: var(--text-dark);
        font-weight: 600;
    }

    .dashboard-card p {
        margin: 0;
        font-size: 1rem;
        color: var(--text-light);
        line-height: 1.5;
    }

    /* --- NEW: Color variant for the "My Room Details" card --- */
    .dashboard-card.details-card {
        border-color: var(--primary-color);
    }
    .dashboard-card.details-card .icon-container {
        background-color: #eaecf4; /* Light blue background */
        color: var(--primary-color); /* Dark blue icon */
    }


    /* --- REFINED: Styles for Message Center --- */
    .message-center {
        background: #fff;
        padding: 20px;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
    }
    .message-center h4 {
        margin-top: 0;
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--text-dark);
    }
    .message-history {
        max-height: 350px;
        overflow-y: auto;
        border: 1px solid var(--border-color);
        padding: 15px;
        border-radius: 8px;
    }
    .message-item {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    .message-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 5px;
    }
    .message-header strong {
        color: var(--primary-color);
    }
    .message-header small {
        color: var(--text-light);
        font-size: 0.75rem;
        background-color: #f8f9fc;
        padding: 2px 8px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }
    .message-item p {
        margin: 0 0 8px 0;
        color: var(--text-light);
    }
    .message-footer {
        font-size: 0.75rem;
        color: #b7b9cc;
    }
</style>
@endpush