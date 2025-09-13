<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Store a new broadcast message.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:warden_only,student_only,both',
        ]);

        // 2. Create and save the message
        Message::create([
            'sender_id' => Auth::id(),
            'sender_role' => 'admin',
            'title' => $request->title,
            'body' => $request->body,
            'recipient_type' => $request->recipient_type,
        ]);

        // 3. Redirect back to the dashboard with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Message sent successfully!');
    }
}