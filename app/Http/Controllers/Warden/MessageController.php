<?php

namespace App\Http\Controllers\Warden;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends WardenBaseController
{
    /**
     * Store a new broadcast message from the warden.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // 2. Create and save the message
        Message::create([
            'sender_id' => Auth::id(), // The logged-in warden's ID
            'sender_role' => 'warden',
            'title' => $request->title,
            'body' => $request->body,
            'recipient_type' => 'student_only', // Wardens can only send to students
        ]);

        // 3. Redirect back to the dashboard with a success message
        return redirect()->route('warden.dashboard')->with('success', 'Message sent successfully!');
    }
}