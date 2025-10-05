@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<div class="stat-grid">
    <div class="stat-card students">
        <div class="stat-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h4>Total Students</h4>
            <p>{{ $studentCount }}</p>
        </div>
    </div>
    <div class="stat-card wardens">
        <div class="stat-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <h4>Total Wardens</h4>
            <p>{{ $wardenCount }}</p>
        </div>
    </div>
    <div class="stat-card hostels">
        <div class="stat-icon">
            <i class="fas fa-hotel"></i>
        </div>
        <div class="stat-info">
            <h4>Total Hostels</h4>
            <p>{{ $hostelCount }}</p>
        </div>
    </div>
    <div class="stat-card rooms">
        <div class="stat-icon">
            <i class="fas fa-door-open"></i>
        </div>
        <div class="stat-info">
            <h4>Available Rooms</h4>
            <p>{{ $availableRoomsCount }}</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h4>Students by Faculty</h4>
        <canvas id="facultyChart"></canvas>
    </div>

    <div class="message-center">
        <h4>Announcements & Notices</h4>
        
        <form action="{{ route('admin.messages.store') }}" method="POST" id="messageForm" class="message-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="recipient_type" id="recipient_type" value="both">
            
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="message_title" placeholder="Enter a title..." required>
            </div>
            <div class="form-group">
                <label for="body">Message</label>
                <textarea name="body" id="message_body" rows="4" placeholder="Type your message here..." required></textarea>
            </div>
            <div class="form-group">
                <label for="attachment">Attachment (Optional PDF)</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="attachment" id="attachment" class="hidden-file-input" accept=".pdf">
                    <button type="button" class="btn-custom-file-upload">
                        <i class="fas fa-upload"></i> Choose File
                    </button>
                    <span class="file-name" id="file-name">No file chosen</span>
                </div>
            </div>
            <div class="message-buttons">
                <button type="button" class="btn-send" data-recipient="warden_only">Wardens</button>
                <button type="button" class="btn-send" data-recipient="student_only">Students</button>
                <button type="button" class="btn-send active" data-recipient="both">Both</button>

                <button type="submit" id="realSubmitBtn" style="display: none;"></button>
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
                    @if($message->attachment_path)
                        <div class="message-attachment">
                            <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank">
                                <i class="fas fa-file-pdf"></i> View Attachment
                            </a>
                        </div>
                    @endif
                    <small class="message-footer">{{ $message->created_at->format('M d, Y H:i A') }}</small>
                </div>
            @empty
                <p style="text-align: center; color: #858796; padding-top: 20px;">No messages sent yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div id="confirmMessageModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Message</h3>
        <p id="modal-message-text">Are you sure you want to send this message?</p>
        <div class="modal-buttons">
            <button type="button" id="cancel-send-btn" class="btn-cancel">Cancel</button>
            <button type="button" id="confirm-send-btn" class="btn-confirm">Yes, Send</button>
        </div>
    </div>
</div>

<div id="alertModal" class="modal">
    <div class="modal-content">
        <h3 style="color: #e74a3b; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fas fa-exclamation-triangle"></i> Incomplete Message
        </h3>
        <p id="alert-modal-text">Please fill in both the title and message fields before sending.</p>
        <div class="modal-buttons" style="justify-content: center;">
            <button type="button" id="close-alert-btn" class="btn-confirm">Close</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

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

    const facultyChart = document.getElementById('facultyChart');
    if (facultyChart) {
        const ctx = facultyChart.getContext('2d');
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
    }

    const messageForm = document.getElementById('messageForm');
    const messageTitle = document.getElementById('message_title');
    const messageBody = document.getElementById('message_body');
    const messageButtons = document.querySelectorAll('.message-buttons .btn-send');
    const recipientInput = document.getElementById('recipient_type');
    
    const confirmModal = document.getElementById('confirmMessageModal');
    const alertModal = document.getElementById('alertModal');

    const modalText = document.getElementById('modal-message-text');
    const cancelSendBtn = document.getElementById('cancel-send-btn');
    const confirmSendBtn = document.getElementById('confirm-send-btn');
    const closeAlertBtn = document.getElementById('close-alert-btn');

    messageButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Stop form submission

            // Check for empty fields before showing modal
            if (!messageTitle.value.trim() || !messageBody.value.trim()) {
                if (alertModal) alertModal.style.display = 'block';
                return;
            }

            // Update recipient input and button styles
            const recipient = this.dataset.recipient;
            recipientInput.value = recipient;
            messageButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Set dynamic modal message
            let confirmationMessage = '';
            if (recipient === 'warden_only') {
                confirmationMessage = "Do you need to send this message for Wardens Only?";
            } else if (recipient === 'student_only') {
                confirmationMessage = "Do you need to send this message for Students Only?";
            } else {
                confirmationMessage = "Do you need to send this message for Both Wardens and Students?";
            }
            modalText.textContent = confirmationMessage;

            // Show the modal
            confirmModal.style.display = 'block';
        });
    });

    if (confirmSendBtn) {
        confirmSendBtn.addEventListener('click', function() {
            document.getElementById('realSubmitBtn').click();
        });
    }

    if (cancelSendBtn) {
        cancelSendBtn.addEventListener('click', function() {
            if(confirmModal) confirmModal.style.display = 'none';
        });
    }

    if (closeAlertBtn) {
        closeAlertBtn.addEventListener('click', function() {
            if(alertModal) alertModal.style.display = 'none';
        });
    }

    const attachmentInput = document.getElementById('attachment');
    const fileNameSpan = document.getElementById('file-name');
    const customFileUploadBtn = document.querySelector('.btn-custom-file-upload');

    // Trigger click on hidden input when custom button is clicked
    if (customFileUploadBtn) {
        customFileUploadBtn.addEventListener('click', function() {
            attachmentInput.click();
        });
    }

    // Update the displayed file name when a file is selected
    if (attachmentInput && fileNameSpan) {
        attachmentInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                fileNameSpan.textContent = this.files[0].name;
            } else {
                fileNameSpan.textContent = 'No file chosen';
            }
        });
    }
});
</script>
@endsection

@push('styles')
<style>
    :root {
    --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    --card-radius: 12px;
    --text-dark: #3a3b45;
    --text-light: #858796;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 25px;
        margin-bottom: 25px; 
    }

    .stat-card {
        background-color: #ffffff;
        padding: 20px;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 15px; 
        border-left: 5px solid transparent;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        font-size: 1.75rem; 
        width: 55px;    
        height: 55px;   
        display: grid;
        place-items: center;
        border-radius: 50%;
    }

    .stat-info h4 {
        margin: 0 0 5px 0;
        color: var(--text-light);
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .stat-info p {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-dark);
    }

    .stat-card.students {
        border-color: #4e73df;
    }
    .stat-card.students .stat-icon {
        color: #4e73df;
        background-color: #e6eafb;
    }

    .stat-card.wardens {
        border-color: #f6c23e;
    }
    .stat-card.wardens .stat-icon {
        color: #f6c23e;
        background-color: #fff8e5;
    }

    .stat-card.hostels {
        border-color: #1cc88a;
    }
    .stat-card.hostels .stat-icon {
        color: #1cc88a;
        background-color: #e2f7f0;
    }

    .stat-card.rooms {
        border-color: #e74a3b;
    }
    .stat-card.rooms .stat-icon {
        color: #e74a3b;
        background-color: #fce8e6;
    }

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

    .modal {
    display: none; position: fixed; z-index: 1050; left: 0; top: 0;
    width: 100%; height: 100%; overflow: hidden;
    background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
    }
    .modal-content {
        position: relative; margin: 10% auto; padding: 2rem;
        width: 90%; max-width: 450px; background-color: #fff;
        border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,.3);
        text-align: center; animation: fadeIn 0.3s;
    }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .modal-content h3 { margin-top: 0; margin-bottom: 1rem; font-size: 1.5rem; color: var(--text-dark); }
    .modal-content p { margin-bottom: 2rem; color: var(--text-light); }
    .modal-buttons { display: flex; gap: 1rem; justify-content: center; }
    .modal-buttons button {
        flex: 1; padding: 10px; border: none; cursor: pointer;
        border-radius: 5px; transition: all 0.2s; font-weight: 600;
    }
    .modal-buttons .btn-cancel { background-color: #858796; color: white; }
    .modal-buttons .btn-cancel:hover { background-color: #717384; }
    .modal-buttons .btn-confirm { background-color: #4e73df; color: white; }
    .modal-buttons .btn-confirm:hover { background-color: #2e59d9; }

    .form-control-file {
        display: block;
        width: 100%;
        padding: 8px;
        font-size: 0.9rem;
        border: 1px solid #d1d3e2;
        border-radius: 5px;
        background-color: #fff;
    }
    .message-attachment {
        margin-top: 8px;
    }
    .message-attachment a {
        color: #e74a3b;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .message-attachment a:hover {
        text-decoration: underline;
    }
    .message-attachment i {
        margin-right: 5px;
    }

    .custom-file-input-wrapper {
    display: flex;
    align-items: center;
    border: 1px solid #d1d3e2;
    border-radius: 5px;
    background-color: #fff;
    padding: 5px; /* Adjust padding to fit the button and text */
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
    transition: border-color 0.2s, box-shadow 0.2s;
    }
    .custom-file-input-wrapper:focus-within {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
    }

    .hidden-file-input {
        /* Visually hide the input, but keep it accessible for screen readers */
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .btn-custom-file-upload {
        background-color: #4e73df; /* Primary blue color */
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px; /* Space between icon and text */
        transition: background-color 0.2s ease;
    }
    .btn-custom-file-upload:hover {
        background-color: #2e59d9; /* Darker blue on hover */
    }
    .btn-custom-file-upload i {
        font-size: 1rem;
    }

    .file-name {
        margin-left: 10px;
        color: #5a5c69;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis; /* Add ellipsis for long file names */
        flex-grow: 1; /* Allows the text to take up available space */
        font-size: 0.9rem;
    }
</style>