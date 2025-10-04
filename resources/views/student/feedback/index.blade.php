@extends('student.layout')
@section('title', 'Feedback')
@section('page-title', 'Hostel Feedback Form')

@section('content')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
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
});
</script>
@endpush

<style>
    .feedback-container {
        max-width: 850px;
        margin: auto;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        border: 1px solid transparent;
        font-weight: 500;
    }
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .feedback-form-card {
        background-color: #fff;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: 1px solid #e3e6f0;
    }
    
    .feedback-intro {
        text-align: center; 
        color: #858796; 
        margin-bottom: 2.5rem;
        font-size: 1.05rem;
        line-height: 1.6;
    }

    .feedback-question-card {
        background-color: #fdfdff;
        padding: 1.5rem 2rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        border: 1px solid #e3e6f0;
        border-left: 4px solid #e3e6f0;
        transition: all 0.3s ease-in-out;
    }
    .feedback-question-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.07);
        border-left-color: #4e73df;
    }

    .feedback-question-card h3 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        font-weight: 600;
        color: #3a3b45;
        font-size: 1.2rem;
    }

    .rating-group {
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .rating-group label {
        position: relative;
        cursor: pointer;
        text-align: center;
        transition: transform 0.2s ease, filter 0.2s ease;
        width: 120px;
        filter: grayscale(50%); 
    }
    .rating-group label:hover {
        transform: scale(1.1);
        filter: grayscale(0%);
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
        transition: all 0.3s ease;
    }
    .rating-text {
        font-weight: 600;
        color: #858796;
        margin-top: 5px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .rating-number {
        position: absolute;
        top: -10px;
        right: 20px;
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
        transition: all 0.3s ease;
    }
    
    .rating-group input[type="radio"]:checked + .rating-icon { stroke: #4e73df; }
    .rating-group input[type="radio"]:checked ~ .rating-number { background-color: #4e73df; border-color: #4e73df; color: #fff; transform: scale(1.1); }
    .rating-group input[type="radio"]:checked ~ .rating-text { color: #4e73df; font-weight: 700; }
    .rating-group input[type="radio"]:checked + .rating-icon + .rating-number + .rating-text { /* This is a bit complex, but it targets the label of the checked radio */
        transform: scale(1.05);
        filter: grayscale(0%);
    }

    .form-buttons {
        display: flex;
        gap: 1.5rem;
        margin-top: 2.5rem;
        justify-content: center;
    }
    .btn {
        flex-grow: 1;
        max-width: 220px;
        padding: 0.85rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 50px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 15px rgba(0,0,0,0.15);
    }
    .btn-submit { background: linear-gradient(45deg, #1cc88a, #13a26f); color: white; }
    .btn-secondary { background-color: #858796; color: white; }
    
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 15% auto; padding: 30px; border: none; width: 90%; max-width: 450px; border-radius: 12px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: scale(0.95);} to {opacity: 1; transform: scale(1);} }
    .modal-content h3 { margin-top: 0; font-size: 1.5rem; color: #333; }
    .modal-content p { margin-bottom: 1.5rem; color: #5a5c69; }
    .modal-buttons { display: flex; gap: 1rem; justify-content: center; }
    .modal-buttons .btn { flex-grow: 1; max-width: 140px; }
</style>

<div class="feedback-container">
    <div class="feedback-form-card">
        <p class="feedback-intro">Please rate the following aspects of the hostel service from 1 (Very Unsatisfied) to 5 (Very Satisfied).</p>
        
        <form action="{{ route('student.feedback.store') }}" method="POST" id="feedbackForm">
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
</div>

<div id="updateModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Update</h3>
        <p>Are you sure you want to update your previously submitted feedback?</p>
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
