<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    // This method will show the questions management page
    public function questions()
    {
        // We can have a maximum of 10 active questions
        $questions = FeedbackQuestion::where('is_active', true)->orderBy('created_at', 'desc')->get();
        return view('admin.feedback.questions', compact('questions'));
    }

    // This method stores a new question
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:255|unique:feedback_questions,question_text',
        ]);

        if (FeedbackQuestion::where('is_active', true)->count() >= 10) {
            return redirect()->back()->with('error', 'You can only have a maximum of 10 active questions.');
        }

        FeedbackQuestion::create([
            'question_text' => $request->question_text,
            'is_active' => true,
        ]);

        return redirect()->route('admin.feedback.questions')->with('success', 'New feedback question added successfully.');
    }
    
    // This method deletes a question
    public function destroyQuestion(FeedbackQuestion $question)
    {
        try {
            $question->delete();
            return redirect()->route('admin.feedback.questions')->with('success', 'Question deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.feedback.questions')->with('error', 'Failed to delete the question.');
        }
    }

    public function index()
    {
        $questions = FeedbackQuestion::where('is_active', true)->with('responses')->get();
        
        $chartData = [];
        foreach ($questions as $question) {
            // Get the count for each rating (1 to 5)
            $responses = $question->responses()
                ->select('rating', DB::raw('count(*) as count'))
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->all();

            $chartData[] = [
                'question' => $question->question_text,
                'labels' => ['Rating 1', 'Rating 2', 'Rating 3', 'Rating 4', 'Rating 5'],
                'data' => [
                    $responses[1] ?? 0,
                    $responses[2] ?? 0,
                    $responses[3] ?? 0,
                    $responses[4] ?? 0,
                    $responses[5] ?? 0,
                ],
            ];
        }
        
        return view('admin.feedback.index', compact('chartData'));
    }

        // NEW METHOD FOR UPDATING
    public function updateQuestion(Request $request, FeedbackQuestion $question)
    {
        $request->validate([
            'question_text' => ['required', 'string', 'max:255', Rule::unique('feedback_questions')->ignore($question->id)],
        ]);
        
        $question->update(['question_text' => $request->question_text]);
        
        return redirect()->route('admin.feedback.questions')->with('success', 'Question updated successfully.');
    }
}