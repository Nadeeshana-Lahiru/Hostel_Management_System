@extends('admin.layout')

@section('title', 'Manage Feedback Questions')
@section('page-title', '')

@section('content')
<style>
    /* Page Header */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .page-header h2 { margin: 0; font-size: 1.75rem; color: #333; }

    /* Alert Messages */
    .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 5px; border: 1px solid transparent; font-weight: 500; }
    .alert-success { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
    .alert-danger { background-color: #fee2e2; color: #991b1b; border-color: #fecaca; }

    /* Modern Fieldset */
    .fieldset-modern { border: 1px solid #e3e6f0; padding: 1.5rem 2rem; border-radius: 8px; margin-bottom: 2rem; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .legend-modern { font-weight: 600; font-size: 1.2rem; color: #4e73df; padding: 0 10px; }

    /* General Form Styles */
    .form-group label { font-weight: 600; margin-bottom: 0.5rem; color: #5a5c69; display: block; }
    .form-group input[type="text"] {
        width: 100%;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #d1d3e2;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input[type="text"]:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    
    /* General Button Styles */
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Specific Button Colors */
    .btn-primary { background-color: #4e73df; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .btn-warning { background-color: #f6c23e; }
    .btn-danger { background-color: #e74a3b; }
    .btn-submit { background-color: #1cc88a; } /* Green for submission */

    /* Question List */
    .question-list-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e3e6f0; }
    .question-list-item:last-child { border-bottom: none; }
    .question-list-item span { color: #5a5c69; font-size: 1rem; }
    .question-actions { display: flex; gap: 10px; }

    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fefefe; margin: 15% auto; padding: 25px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
    .close-button { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; }
    .modal-content h3 { margin-top: 0; }
</style>

<div class="page-header">
    <h2>Feedback Questions</h2>
    <a href="{{ route('admin.feedback.index') }}" class="btn btn-primary">View Analytics Report</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><strong>Please correct the errors:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<fieldset class="fieldset-modern">
    <legend class="legend-modern">Add New Question</legend>
    <form action="{{ route('admin.feedback.storeQuestion') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="question_text">Question Text</label>
            <input type="text" name="question_text" value="{{ old('question_text') }}" placeholder="e.g., How would you rate the hostel cleanliness?" required>
        </div>
        <button type="submit" class="btn btn-submit" style="margin-top: 15px;">Add Question</button>
    </form>
</fieldset>

<fieldset class="fieldset-modern">
    <legend class="legend-modern">Current Active Questions ({{ $questions->count() }}/10)</legend>
    <ul style="list-style-type: none; padding: 0;">
        @forelse($questions as $question)
            <li class="question-list-item">
                <span>{{ $loop->iteration }}. {{ $question->question_text }}</span>
                <div class="question-actions">
                    <button class="btn btn-warning edit-btn" data-id="{{ $question->id }}" data-text="{{ $question->question_text }}">Edit</button>
                    <form action="{{ route('admin.feedback.destroyQuestion', $question->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </li>
        @empty
            <p>No active feedback questions have been added yet.</p>
        @endforelse
    </ul>
</fieldset>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h3>Edit Question</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="edit_question_text">Question Text</label>
                <input type="text" name="question_text" id="edit_question_text" required>
            </div>
            <button type="submit" class="btn btn-submit" style="margin-top: 15px;">Save Changes</button>
        </form>
    </div>
</div>

<script>
// Your Javascript for the modal remains the same
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editModal');
    const closeBtn = document.querySelector('.close-button');
    const editForm = document.getElementById('editForm');
    const editInput = document.getElementById('edit_question_text');
    const editBtns = document.querySelectorAll('.edit-btn');

    editBtns.forEach(button => {
        button.addEventListener('click', function () {
            const questionId = this.dataset.id;
            const questionText = this.dataset.text;
            
            editForm.action = `/admin/feedback/questions/${questionId}`;
            editInput.value = questionText;
            modal.style.display = 'block';
        });
    });

    closeBtn.onclick = function() { modal.style.display = 'none'; }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>
@endsection