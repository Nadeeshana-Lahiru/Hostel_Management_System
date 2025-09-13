@extends('student.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    
    <a href="{{ route('student.room.index') }}" class="dashboard-card">
        <div class="card-header">
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 8.5 3 3L22 8l-3-3"/></svg>
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
    /* New Styles for the Dashboard Card */
    .dashboard-card {
        background-color: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        width: 80%;
        height: 50%;
        margin-left: 10%;
        margin-top: 10%;
    }
    .dashboard-card:hover {
        transform: scale(1.03); /* Gently zooms the card in */
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        border-left: 5px solid #4e73df;
    }
    .dashboard-card .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .dashboard-card .icon-container {
        background-color: #eaecf4;
        border-radius: 50%;
        padding: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #4e73df;
    }
    .dashboard-card h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #4e73df;
        font-weight: 600;
    }
    .dashboard-card p {
        margin: 0;
        font-size: 1rem;
        color: #5a5c69;
    }

    /* Styles for Message Center (copied from admin dashboard) */
    .message-center { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .message-history { height: 300px; overflow-y: auto; border: 1px solid #e3e6f0; padding: 15px; margin-top: 15px; border-radius: 8px; }
    .message-item { border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 10px; }
    .message-item:last-child { border-bottom: none; margin-bottom: 0; }
    .message-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px; }
    .message-header strong { color: #4e73df; }
    .message-header small { color: #858796; font-size: 0.75rem; background-color: #f8f9fc; padding: 2px 6px; border-radius: 4px; }
    .message-item p { margin: 0 0 5px 0; color: #5a5c69; }
    .message-footer { font-size: 0.75rem; color: #b7b9cc; }
</style>
@endpush