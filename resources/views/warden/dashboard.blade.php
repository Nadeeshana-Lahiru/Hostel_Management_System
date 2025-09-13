@extends('warden.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 20px;">
    <div class="stat-card"><h4>Total Students</h4><p>{{ $studentCount }}</p></div>
    <div class="stat-card"><h4>Available Rooms</h4><p>{{ $availableRoomsCount }}</p></div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h4>Students by Faculty</h4>
        <canvas id="facultyChart"></canvas>
    </div>

    <div class="message-center">
        <h4>Announcements & Notices</h4>
        
        <form action="{{ route('warden.messages.store') }}" method="POST" id="messageForm" class="message-form">
            @csrf
            <input type="hidden" name="recipient_type" id="recipient_type" value="both">
            
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" placeholder="Enter a title..." required>
            </div>
            <div class="form-group">
                <label for="body">Message</label>
                <textarea name="body" rows="4" placeholder="Type your message here..." required></textarea>
            </div>
            <div class="message-buttons">
                <button type="submit" class="btn-send" data-recipient="student_only">Send to Students</button>
            </div>
        </form>

        <h5 style="margin-top: 20px; margin-bottom: 0;">Message History</h5>
        <div class="message-history">
            @forelse($messages as $message)
                <div class="message-item">
                    <div class="message-header">
                        <strong>{{ $message->title }}</strong>
                        <small>{{ ucwords(str_replace('_', ' ', $message->recipient_type)) }}</small>
                    </div>
                    <p>{{ $message->body }}</p>
                    <small class="message-footer">{{ $message->created_at->format('M d, Y H:i A') }}</small>
                </div>
            @empty
                <p style="text-align: center; color: #858796; padding-top: 20px;">No messages sent yet.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Chart.js script
    const ctx = document.getElementById('facultyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($facultyChartLabels),
            datasets: [{
                label: 'Number of Students',
                data: @json($facultyChartData),
                backgroundColor: 'rgba(78, 115, 223, 0.5)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });

    // Message form script
    const messageButtons = document.querySelectorAll('.message-buttons .btn-send');
    const recipientInput = document.getElementById('recipient_type');
    
    messageButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            recipientInput.value = this.dataset.recipient;
            messageButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endsection

@push('styles')
<style>
    /* Main Stat Cards */
    .stat-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .stat-card h4 { margin: 0 0 10px 0; color: #5a5c69; font-size: 1rem; }
    .stat-card p { font-size: 2rem; font-weight: 700; margin: 0; color: #4e73df; }

    /* Message Center Specific Styles */
    .message-center {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .message-form {
        background: #f8f9fc;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
    }
    .message-form .form-group { margin-bottom: 15px; }
    .message-form label {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 5px;
        display: block;
        color: #5a5c69;
    }
    .message-form input, .message-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d3e2;
        border-radius: 5px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .message-form input:focus, .message-form textarea:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
    }
    .message-buttons { display: flex; gap: 10px; margin-top: 15px; }
    .message-buttons .btn-send {
        flex: 1;
        padding: 10px;
        border: 1px solid #d1d3e2;
        background: #fff;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.2s;
        font-weight: 600;
        color: #5a5c69;
    }
    .message-buttons .btn-send:hover {
        background-color: #f1f3f8;
    }
    .message-buttons .btn-send.active {
        background: #4e73df;
        color: white;
        border-color: #4e73df;
    }
    .message-history {
        height: 300px;
        overflow-y: auto;
        border: 1px solid #e3e6f0;
        padding: 15px;
        margin-top: 15px;
        border-radius: 8px;
    }
    .message-item {
        border-bottom: 1px solid #e3e6f0;
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
        color: #4e73df;
    }
    .message-header small {
        color: #858796;
        font-size: 0.75rem;
        background-color: #f8f9fc;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .message-item p {
        margin: 0 0 5px 0;
        color: #5a5c69;
    }
    .message-footer {
        font-size: 0.75rem;
        color: #b7b9cc;
    }
</style>