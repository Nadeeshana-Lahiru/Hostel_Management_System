<?php

namespace App\Http\Controllers\Warden;

use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FeedbackController extends WardenBaseController
{
    /**
     * Display feedback analytics scoped to the warden's hostel.
     */
    public function index()
    {
        if (!$this->hostel) {
            // A view for when the warden isn't assigned to a hostel.
            return view('warden.feedback.index_unassigned');
        }

        // Get the IDs of all students in the warden's hostel.
        $studentIdsInHostel = $this->hostel->rooms()->with('students')->get()->pluck('students.*.id')->flatten()->unique();

        $questions = FeedbackQuestion::where('is_active', true)->with('responses')->get();
        
        $chartData = [];
        foreach ($questions as $question) {
            // Get the response counts for each rating, BUT ONLY from students in this hostel.
            $responses = $question->responses()
                ->whereIn('student_id', $studentIdsInHostel) // This is the crucial scoping line
                ->select('rating', DB::raw('count(*) as count'))
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->all();

            $chartData[] = [
                'question' => $question->question_text,
                'labels' => ['Rating 1', 'Rating 2', 'Rating 3', 'Rating 4', 'Rating 5'],
                'data' => [
                    $responses[1] ?? 0, $responses[2] ?? 0, $responses[3] ?? 0,
                    $responses[4] ?? 0, $responses[5] ?? 0,
                ],
            ];
        }
        
        return view('warden.feedback.index', compact('chartData'));
    }

    /**
     * The following methods for managing questions are global and function
     * identically to the admin's, just with different route names.
     */

    public function questions()
    {
        $questions = FeedbackQuestion::where('is_active', true)->orderBy('created_at', 'desc')->get();
        return view('warden.feedback.questions', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate(['question_text' => 'required|string|max:255|unique:feedback_questions,question_text']);
        if (FeedbackQuestion::where('is_active', true)->count() >= 10) {
            return redirect()->back()->with('error', 'You can only have a maximum of 10 active questions.');
        }
        FeedbackQuestion::create(['question_text' => $request->question_text]);
        return redirect()->route('warden.feedback.questions')->with('success', 'New feedback question added successfully.');
    }
    
    public function updateQuestion(Request $request, FeedbackQuestion $question)
    {
        $request->validate([
            'question_text' => ['required', 'string', 'max:255', Rule::unique('feedback_questions')->ignore($question->id)],
        ]);
        $question->update(['question_text' => $request->question_text]);
        return redirect()->route('warden.feedback.questions')->with('success', 'Question updated successfully.');
    }

    public function destroyQuestion(FeedbackQuestion $question)
    {
        $question->delete();
        return redirect()->route('warden.feedback.questions')->with('success', 'Question deleted successfully.');
    }
}