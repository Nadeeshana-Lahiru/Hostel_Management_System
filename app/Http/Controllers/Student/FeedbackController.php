<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackResponse;

class FeedbackController extends StudentBaseController
{
    public function index()
    {
        $questions = FeedbackQuestion::where('is_active', true)->get();
        // Get existing responses to pre-fill the form
        $existingResponses = $this->student->feedbackResponses()
            ->pluck('rating', 'feedback_question_id');

        return view('student.feedback.index', compact('questions', 'existingResponses'));
    }

    public function store(request $request)
    {
        $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|between:1,5',
        ]);

        foreach ($request->ratings as $questionId => $rating) {
            // Use updateOrCreate to either add a new response or update an existing one
            FeedbackResponse::updateOrCreate(
                [
                    'student_id' => $this->student->id,
                    'feedback_question_id' => $questionId,
                ],
                [
                    'rating' => $rating,
                ]
            );
        }

        return redirect()->route('student.feedback.index')->with('success', 'Your feedback has been saved successfully!');
    }
}
