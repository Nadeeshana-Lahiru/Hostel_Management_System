<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hostel;
use App\Models\Message;
use App\Models\Room;
use App\Models\Student;
use App\Models\Warden;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Basic authentication check
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login.form')->with('error', 'You must be logged in as an admin to access this page.');
        }
        
        // Stats Cards
        $studentCount = Student::count();
        $wardenCount = Warden::count();
        $hostelCount = Hostel::count();
        $availableRoomsCount = Room::whereDoesntHave('students', function ($query) {
            $query->select(DB::raw('count(room_id) as students_count'))
                  ->having('students_count', '>=', DB::raw('capacity'));
        })->count();

        // Faculty Chart Data
        $facultyData = Student::select('faculty', DB::raw('count(*) as count'))
            ->groupBy('faculty')->pluck('count', 'faculty');
            
        $facultyChartLabels = $facultyData->keys();
        $facultyChartData = $facultyData->values();
        
        // Messages
        $messages = Message::latest()->take(5)->get(); // Get last 5 messages

        return view('admin.dashboard', compact(
            'studentCount', 'wardenCount', 'hostelCount', 'availableRoomsCount',
            'facultyChartLabels', 'facultyChartData', 'messages'
        ));
    }
}