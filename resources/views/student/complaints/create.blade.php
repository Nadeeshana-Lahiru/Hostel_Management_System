@extends('student.layout')

@section('title', 'Submit a Complaint')
@section('page-title', 'Submit a New Complaint')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: auto;
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .full-width { grid-column: 1 / -1; }
    .two-thirds-width { grid-column: span 2; }

    fieldset {
        border: 1px solid #e3e6f0;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        background-color: #f8f9fc;
    }
    legend {
        font-weight: 600;
        font-size: 1.1rem;
        color: #4e73df;
        padding: 0 10px;
        background-color: #f8f9fc;
        position: relative;
        top: -2.5rem;
        left: 1rem;
        width: auto;
    }
    label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #5a5c69;
    }
    input[type="text"], input[type="email"], input[type="date"], select, textarea {
        width: 100%;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #d1d3e2;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
    }
    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        width: 50%;
        margin-left: 25%;
    }
    .btn {
        flex-grow: 1; /* Makes buttons share space equally */
        padding: 0.85rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border: none;
        transition: all 0.2s;
    }
    .btn-submit { background-color: #4e73df; color: white; }
    .btn-submit:hover { background-color: #2e59d9; }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-secondary:hover { background-color: #717384; }

    #image-preview-container {
        margin-top: 10px;
        display: none; /* Hidden by default */
    }
    #image-preview {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
</style>

<div class="form-container" style="max-width: 700px;">
    <form action="{{ route('student.complaints.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset class="fieldset-modern">
            <legend class="legend-modern">Complaint Details</legend>

            <div class="form-group">
                <label for="type">Type of Complaint</label>
                <select name="type" id="type" required>
                    <option value="">Select a category...</option>
                    <option value="Electronic">Electronic</option>
                    <option value="Washroom">Washroom</option>
                    <option value="Water Supply">Water Supply</option>
                    <option value="Canteen">Canteen</option>
                    <option value="Plumbing">Plumbing</option>
                    <option value="Hostel Room">Hostel Room</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="message">Description</label>
                <textarea name="message" id="message" rows="6" placeholder="Please describe the issue in detail..." required>{{ old('message') }}</textarea>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="image">Attach an Image (Optional)</label>
                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
                
                <div id="image-preview-container">
                    <img id="image-preview" src="#" alt="Image Preview"/>
                </div>
            </div>
        </fieldset>

        <div class="form-buttons" style="width: 100%; margin-left: 0;">
            <button type="submit" class="btn btn-submit">Submit Complaint</button>
            <a href="{{ route('student.complaints.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById('image-preview');
        const previewContainer = document.getElementById('image-preview-container');
        
        reader.onload = function(){
            if(reader.readyState == 2){
                previewContainer.style.display = 'block';
                imageField.src = reader.result;
            }
        }
        
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        } else {
            previewContainer.style.display = 'none';
            imageField.src = "#";
        }
    }
</script>
@endsection