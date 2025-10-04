@extends('student.layout')

@section('title', 'Submit a Complaint')
@section('page-title', 'Submit a New Complaint')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="form-container">
    <form id="complaintForm" action="{{ route('student.complaints.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <fieldset>
            <legend>Complaint Details</legend>

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type of Complaint</label>
                    <div class="select-wrapper">
                        <select name="type" id="type" required>
                            <option value="" disabled selected>Select a category...</option>
                            <option value="Electronic">Electronic</option>
                            <option value="Washroom">Washroom</option>
                            <option value="Water Supply">Water Supply</option>
                            <option value="Canteen">Canteen</option>
                            <option value="Plumbing">Plumbing</option>
                            <option value="Hostel Room">Hostel Room</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Attach an Image (Optional)</label>
                    <div class="file-upload-wrapper">
                        <label for="image" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="file-name">Choose a file...</span>
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
                    </div>
                </div>
            </div>

            <div id="image-preview-container">
                <img id="image-preview" src="#" alt="Image Preview"/>
            </div>

            <div class="form-group full-width">
                <label for="message">Description</label>
                <textarea name="message" id="message" rows="6" placeholder="Please describe the issue in detail..." required>{{ old('message') }}</textarea>
            </div>
        </fieldset>

        <div class="form-buttons">
            <button type="button" id="submitBtn" class="btn btn-submit">
                <i class="fas fa-paper-plane"></i> Submit Complaint
            </button>
            <a href="{{ route('student.complaints.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Submission</h3>
        <p>Do you need to post a new complaint?</p>
        <div class="modal-buttons">
            <button type="button" id="cancel-btn" class="btn btn-secondary">Cancel</button>
            <button type="button" id="confirm-send-btn" class="btn btn-submit">Yes, Send</button>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById('image-preview');
        const previewContainer = document.getElementById('image-preview-container');
        const fileNameSpan = document.getElementById('file-name');
        
        reader.onload = function(){
            if(reader.readyState == 2){
                previewContainer.style.display = 'flex';
                imageField.src = reader.result;
            }
        }
        
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
            fileNameSpan.textContent = event.target.files[0].name; 
        } else {
            previewContainer.style.display = 'none';
            imageField.src = "#";
            fileNameSpan.textContent = "Choose a file..."; 
        }

        const complaintForm = document.getElementById('complaintForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmModal = document.getElementById('confirmModal');
    const cancelBtn = document.getElementById('cancel-btn');
    const confirmSendBtn = document.getElementById('confirm-send-btn');

    // Show the modal when the main submit button is clicked
    submitBtn.addEventListener('click', function (event) {
        event.preventDefault(); // Stop the form from submitting
        confirmModal.style.display = 'block';
    });

    // Hide the modal when the "Cancel" button is clicked
    cancelBtn.addEventListener('click', function () {
        confirmModal.style.display = 'none';
    });

    // Submit the form when the "Yes, Send" button is clicked
    confirmSendBtn.addEventListener('click', function () {
        complaintForm.submit();
    });

    // Hide the modal if the user clicks outside of it
    window.addEventListener('click', function (event) {
        if (event.target == confirmModal) {
            confirmModal.style.display = 'none';
        }
    });
    }
</script>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #4e73df;
        --primary-hover: #2e59d9;
        --secondary-color: #858796;
        --secondary-hover: #717384;
        --light-bg: #f8f9fc;
        --border-color: #d1d3e2;
        --text-dark: #3a3b45;
        --text-light: #5a5c69;
        --card-shadow: 0 4px 12px rgba(0,0,0,0.1);
        --input-focus-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }

    .form-container {
        max-width: 900px;
        margin: auto;
        background-color: #fff;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    fieldset {
        border: none;
        padding: 0;
        margin: 0;
    }

    legend {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--text-dark);
        margin-bottom: 2rem;
        width: 100%;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    .full-width {
        margin-bottom: 1.5rem;
    }

    label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-light);
        font-size: 0.9rem;
    }

    input[type="text"], input[type="email"], select, textarea {
        width: 100%;
        padding: 0.85rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background-color: var(--light-bg);
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
        color: var(--text-dark);
        font-size: 1rem;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: var(--input-focus-shadow);
        background-color: #fff;
    }

    .select-wrapper {
        position: relative;
    }
    .select-wrapper::after {
        content: '\f078'; 
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--secondary-color);
    }
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding-right: 2.5rem;
    }

    .file-upload-wrapper input[type="file"] {
        display: none;
    }
    .file-upload-label {
        display: flex;
        align-items: center;
        padding: 0.85rem;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background-color: var(--light-bg);
        color: var(--text-light);
    }
    .file-upload-label:hover {
        border-color: var(--primary-color);
        background-color: #fff;
        color: var(--primary-color);
    }
    .file-upload-label i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    #file-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #image-preview-container {
        display: none; 
        justify-content: center;
        align-items: center;
        margin-bottom: 1.5rem;
        background-color: var(--light-bg);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    #image-preview {
        max-width: 100%;
        max-height: 250px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        border-top: 1px solid var(--border-color);
        padding-top: 2rem;
        justify-content: flex-end; 
    }

    .btn {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        padding: 0.9rem 1.5rem; 
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border: none;
        transition: all 0.2s ease-in-out;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-submit { background-color: var(--primary-color); color: white; }
    .btn-submit:hover { background-color: var(--primary-hover); }
    .btn-secondary { background-color: var(--secondary-color); color: white; }
    .btn-secondary:hover { background-color: var(--secondary-hover); }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-container {
            padding: 1.5rem;
        }
        .form-buttons {
            flex-direction: column;
        }
    }

    .modal {
    display: none; 
    position: fixed; 
    z-index: 1050; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    }
    .modal-content {
        position: relative;
        margin: 10% auto;
        padding: 2rem;
        width: 90%;
        max-width: 450px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,.3);
        text-align: center;
        animation: fadeIn 0.3s;
    }
    .modal-content h3 {
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: var(--text-dark);
    }
    .modal-content p {
        margin-bottom: 2rem;
        color: var(--text-light);
    }
    .modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
</style>
@endpush