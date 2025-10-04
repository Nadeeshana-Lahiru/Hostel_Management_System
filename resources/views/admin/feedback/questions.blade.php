@extends('admin.layout')

@section('title', 'Manage Feedback Questions')
@section('page-title', 'Manage Questions')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .page-header h2 { margin: 0; font-size: 1.75rem; color: #333; }

    .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 5px; border: 1px solid transparent; font-weight: 500; }
    .alert-success { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
    .alert-danger { background-color: #fee2e2; color: #991b1b; border-color: #fecaca; }

    .fieldset-modern { border: 1px solid #e3e6f0; padding: 1.5rem 2rem; border-radius: 8px; margin-bottom: 2rem; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .legend-modern { font-weight: 600; font-size: 1.2rem; color: #4e73df; padding: 0 10px; width: auto; }

    .form-group label { font-weight: 600; margin-bottom: 0.5rem; color: #5a5c69; display: block; }
    .form-group input[type="text"] {
        width: 100%; padding: 0.75rem; border-radius: 5px; border: 1px solid #d1d3e2;
        box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input[type="text"]:focus {
        outline: none; border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    
    .btn {
        display: inline-flex; align-items: center; gap: 6px; 
        padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: normal;
        border-radius: 5px; text-decoration: none; color: white;
        border: none; cursor: pointer; transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .btn i { font-size: 0.9em; }

    .btn-primary,
    .btn-submit { 
        background-color: #4e73df;
    }
    .btn-primary:hover,
    .btn-submit:hover {
        background-color: #2e59d9;
        transform: translateY(-2px); 
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
    }
    .btn-warning {
        background-color: #f6c23e;
    }
    .btn-warning:hover {
        background-color: #dda20a;
    }

    .btn-danger {
        background-color: #e74a3b;
    }
    .btn-danger:hover {
        background-color: #be2617;
    }
    
    .btn-secondary {
        background-color: #858796;
    }
    .btn-secondary:hover {
        background-color: #717384;
        transform: translateY(-2px); 
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
    }

    .question-actions .btn {
        padding: 0.5rem 0.6rem;
    }
    .btn-text {
        color: white; 
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        white-space: nowrap;
        padding-left: 0;
    }
    .question-actions .btn:hover .btn-text {
        color: white; 
        max-width: 100px;
        opacity: 1;
        padding-left: 0.4rem;
    }

    .question-list-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e3e6f0; }
    .question-list-item:last-child { border-bottom: none; }
    .question-list-item span { color: #5a5c69; font-size: 1rem; }
    .question-actions { display: flex; gap: 10px; }

    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 25px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; margin-bottom: 1rem; border-bottom: 1px solid #e3e6f0; }
    .modal-header h3 { margin: 0; font-size: 1.5rem; color: #333; }
    .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; }
    .close-button:hover { color: #333; }
</style>

<div class="page-header">
    <h2>Feedback Questions</h2>
    <a href="{{ route('admin.feedback.index') }}" class="btn btn-primary"><i class="fas fa-chart-bar"></i> View Analytics Report</a>
</div>

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
        <button type="submit" class="btn btn-submit" style="margin-top: 15px;"><i class="fas fa-plus"></i> Add Question</button>
    </form>
</fieldset>

<fieldset class="fieldset-modern">
    <legend class="legend-modern">Current Active Questions ({{ $questions->count() }}/10)</legend>
    <ul style="list-style-type: none; padding: 0;">
        @forelse($questions as $question)
            <li class="question-list-item">
                <span>{{ $loop->iteration }}. {{ $question->question_text }}</span>
                <div class="question-actions">
                    <button class="btn btn-warning edit-btn" data-id="{{ $question->id }}" data-text="{{ $question->question_text }}">
                        <i class="fas fa-edit"></i>
                        <span class="btn-text">Edit</span>
                    </button>
                    <form action="{{ route('admin.feedback.destroyQuestion', $question->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i>
                            <span class="btn-text">Delete</span>
                        </button>
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
        <div class="modal-header">
            <h3>Edit Question</h3>
            <span class="close-button">&times;</span>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="edit_question_text">Question Text</label>
                <input type="text" name="question_text" id="edit_question_text" required>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                <button type="button" id="cancel-edit-btn" class="btn btn-secondary">Cancel</button>
                <button type="button" id="save-changes-btn" class="btn btn-submit"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div id="confirmSaveModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Changes</h3>
        </div>
        <p style="margin-top: 1rem; margin-bottom: 2rem;">Do you need to make this change?</p>
        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button id="cancel-save-btn" class="btn btn-secondary">Cancel</button>
            <button id="confirm-save-btn" class="btn btn-submit">Yes, Save</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editModal');
    const confirmSaveModal = document.getElementById('confirmSaveModal');
    
    const editForm = document.getElementById('editForm');
    const editInput = document.getElementById('edit_question_text');
    
    const openEditBtns = document.querySelectorAll('.edit-btn');
    const closeEditModalBtn = editModal.querySelector('.close-button');
    const cancelEditBtn = document.getElementById('cancel-edit-btn');
    const saveChangesBtn = document.getElementById('save-changes-btn');
    const cancelSaveBtn = document.getElementById('cancel-save-btn');
    const confirmSaveBtn = document.getElementById('confirm-save-btn');

    openEditBtns.forEach(button => {
        button.addEventListener('click', function () {
            const questionId = this.dataset.id;
            const questionText = this.dataset.text;
            
            editForm.action = `/admin/feedback/questions/${questionId}`;
            editInput.value = questionText;
            
            editModal.style.display = 'block';
        });
    });

    const closeEditModal = () => { editModal.style.display = 'none'; };
    closeEditModalBtn.onclick = closeEditModal;
    cancelEditBtn.onclick = closeEditModal;

    saveChangesBtn.addEventListener('click', function() {
        editModal.style.display = 'none';
        confirmSaveModal.style.display = 'block';
    });
    
    const closeConfirmModal = () => { confirmSaveModal.style.display = 'none'; };
    cancelSaveBtn.onclick = closeConfirmModal;

    confirmSaveBtn.addEventListener('click', function() {
        editForm.submit();
    });

    window.onclick = function(event) {
        if (event.target == editModal) {
            closeEditModal();
        }
        if (event.target == confirmSaveModal) {
            closeConfirmModal();
        }
    }
});
</script>
</script>
@endsection