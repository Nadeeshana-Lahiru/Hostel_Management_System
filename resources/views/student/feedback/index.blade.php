@extends('student.layout')
@section('title', 'Feedback')
@section('page-title', 'Hostel Feedback Form')

@section('content')
<style>

    /* Alert Message Styles */
    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 5px;
        border: 1px solid transparent;
        font-weight: 500;
    }
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }
    /* Main Form Container */
    .feedback-form-container {
        max-width: 800px;
        margin: auto;
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    /* Individual Question Card */
    .feedback-question-card {
        background-color: #f8f9fc;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid #e3e6f0;
    }
    .feedback-question-card h5 {
        margin-top: 0;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #5a5c69;
    }

    .rating-group {
        display: flex;
        /* This will space the items out evenly */
        justify-content: space-around;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    .rating-group label {
        position: relative;
        cursor: pointer;
        text-align: center;
        transition: transform 0.2s ease;
        width: 120px; /* Give each item a fixed width for better alignment */
    }
    .rating-group label:hover {
        transform: scale(1.1);
    }
    .rating-group input[type="radio"] {
        opacity: 0;
        position: absolute;
    }
    .rating-icon {
        width: 60px;
        height: 60px;
        stroke: #858796;
        stroke-width: 8;
        transition: all 0.2s ease;
    }
    .rating-text {
        font-weight: 600;
        color: #858796;
        margin-top: 5px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    .rating-number {
        position: absolute;
        top: -10px;
        right: 20px; /* Adjusted position */
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #d1d3e2;
        color: #5a5c69;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    /* Checked State Styling */
    .rating-group input[type="radio"]:checked + .rating-icon { stroke: #4e73df; }
    .rating-group input[type="radio"]:checked ~ .rating-number { background-color: #4e73df; border-color: #4e73df; color: #fff; }
    .rating-group input[type="radio"]:checked ~ .rating-text { color: #4e73df; }

    /* Button Container */
    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn {
        flex-grow: 1;
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
    .btn-submit { background-color: #1cc88a; color: white; }
    .btn-submit:hover { background-color: #17a673; }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-secondary:hover { background-color: #717384; }

    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
    }
    .modal-content {
        background-color: #fefefe; margin: 15% auto; padding: 25px; border: 1px solid #888;
        width: 90%; max-width: 450px; border-radius: 8px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s;
    }
    @keyframes fadeIn { from {opacity: 0; transform: scale(0.95);} to {opacity: 1; transform: scale(1);} }
    .modal-content h3 { margin-top: 0; font-size: 1.5rem; color: #333; }
    .modal-content p { margin-bottom: 1.5rem; color: #5a5c69; }
    .modal-buttons { display: flex; gap: 1rem; justify-content: center; }
    .modal-buttons .btn { flex-grow: 1; max-width: 120px; }
</style>

<div class="feedback-form-container">
    <!-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif -->
    <p style="text-align: center; color: #858796; margin-bottom: 2rem;">Please rate the following aspects of the hostel service from 1 (Very Unsatisfied) to 5 (Very Satisfied).</p>
    <form action="{{ route('student.feedback.store') }}" method="POST"  id="feedbackForm">
        @csrf
        @php
            $ratingsInfo = [
                1 => ['text' => 'Very Unsatisfied', 'emoji' => '<circle cx="64" cy="64" r="56"/><circle cx="44" cy="48" r="8"/><circle cx="84" cy="48" r="8"/><path d="M40,72 a30,30 0 0,1 48,0"/>'],
                2 => ['text' => 'Unsatisfied',     'emoji' => '<circle cx="64" cy="64" r="56"/><circle cx="44" cy="48" r="8"/><circle cx="84" cy="48" r="8"/><path d="M44,76 a24,24 0 0,1 40,0"/>'],
                3 => ['text' => 'Neutral',         'emoji' => '<circle cx="64" cy="64" r="56"/><circle cx="44" cy="48" r="8"/><circle cx="84" cy="48" r="8"/><line x1="40" y1="80" x2="88" y2="80"/>'],
                4 => ['text' => 'Satisfied',       'emoji' => '<circle cx="64" cy="64" r="56"/><circle cx="44" cy="48" r="8"/><circle cx="84" cy="48" r="8"/><path d="M44,76 a24,24 0 0,0 40,0"/>'],
                5 => ['text' => 'Very Satisfied',  'emoji' => '<circle cx="64" cy="64" r="56"/><circle cx="44" cy="48" r="8"/><circle cx="84" cy="48" r="8"/><path d="M40,84 a30,30 0 0,0 48,0"/>']
            ];
        @endphp

        @forelse($questions as $question)
            <div class="feedback-question-card">
                <h3>{{ $loop->iteration }}. {{ $question->question_text }}</h3>
                <div class="rating-group">
                    @foreach($ratingsInfo as $i => $info)
                        <label>
                            <input type="radio" name="ratings[{{ $question->id }}]" value="{{ $i }}" 
                                   {{ ($existingResponses[$question->id] ?? 0) == $i ? 'checked' : '' }} required>
                            <svg class="rating-icon" viewBox="0 0 128 128" fill="none">
                                {!! $info['emoji'] !!}
                            </svg>
                            <div class="rating-number">{{ $i }}</div>
                            <div class="rating-text">{{ $info['text'] }}</div>
                        </label>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="feedback-question-card" style="text-align: center;">
                <p>There are currently no active feedback questions.</p>
            </div>
        @endforelse

        @if(count($questions) > 0)
        <div class="form-buttons">
            @if($existingResponses->isNotEmpty())
                <button type="button" id="updateFeedbackBtn" class="btn btn-submit">Update Feedback</button>
            @else
                <button type="submit" class="btn btn-submit">Submit Feedback</button>
            @endif
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
        @endif
    </form>
</div>

<div id="updateModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Update</h3>
        <p>Do you need to update your feedback?</p>
        <div class="modal-buttons">
            <button id="cancel-update" class="btn btn-secondary">Cancel</button>
            <button id="confirm-update" class="btn btn-submit">Yes, Update</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateBtn = document.getElementById('updateFeedbackBtn');
    
    if (updateBtn) {
        const modal = document.getElementById('updateModal');
        const cancelBtn = document.getElementById('cancel-update');
        const confirmBtn = document.getElementById('confirm-update');
        const feedbackForm = document.getElementById('feedbackForm');

        updateBtn.addEventListener('click', function () {
            modal.style.display = 'block';
        });

        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        }

        confirmBtn.onclick = function() {
            feedbackForm.submit();
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }
});
</script>
@endpush