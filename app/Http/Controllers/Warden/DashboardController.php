<?php

namespace App\Http\Controllers\Warden;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Manual security check: Ensure user is a logged-in warden
        if (!Auth::check() || Auth::user()->role !== 'warden') {
            return redirect()->route('login.form')->with('error', 'Access Denied.');
        }

        // Find the hostel assigned to this specific warden
        $warden = Auth::user()->warden;
        $hostel = Hostel::where('warden_id', $warden->id)->first();

        // If the warden is not assigned to any hostel, show a special message
        if (!$hostel) {
            return view('warden.dashboard_unassigned');
        }

        // Get students and rooms belonging ONLY to this hostel
        $studentsQuery = $hostel->students();
        $roomsQuery = $hostel->rooms();

        // --- Calculate Scoped Stats ---
        $studentCount = $studentsQuery->count();

        // Count rooms that have less than their capacity (e.g., less than 4 students)
        $availableRoomsCount = $roomsQuery->withCount('students')->get()->where('students_count', '<', 4)->count();
        
        // --- Generate Scoped Chart Data ---
        $facultyData = $studentsQuery->select('faculty', DB::raw('count(*) as count'))
            ->groupBy('faculty')->pluck('count', 'faculty');
        
        $facultyChartLabels = $facultyData->keys();
        $facultyChartData = $facultyData->values();

        // This new query fetches all messages relevant to the current warden.
        $wardenUserId = Auth::id();

        $messages = Message::where(function ($query) use ($wardenUserId) {
            // Condition 1: Messages FROM the Admin TO wardens or everyone
            $query->where('sender_role', 'admin')
                  ->whereIn('recipient_type', ['warden_only', 'both']);
    
            // Condition 2: OR messages sent BY the currently logged-in warden
            $query->orWhere(function ($subQuery) use ($wardenUserId) {
                $subQuery->where('sender_role', 'warden')
                         ->where('sender_id', $wardenUserId);
            });
        })
        ->latest() // Order by the newest first
        ->take(15)   // Get the last 15 messages
        ->get();

        return view('warden.dashboard', compact(
            'studentCount', 'availableRoomsCount',
            'facultyChartLabels', 'facultyChartData', 'messages'
        ));
    }
}