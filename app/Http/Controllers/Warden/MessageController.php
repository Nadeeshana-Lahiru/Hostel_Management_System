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
            'recipient_type' => 'required|in:student_only', // Wardens can only send to students
            'attachment' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB PDF
        ]);

        // 2. Create and save the message
        $messageData = [
            'sender_id' => Auth::id(),
            'sender_role' => 'warden', // Correctly set the sender role
            'title' => $request->title,
            'body' => $request->body,
            'recipient_type' => $request->recipient_type,
            'attachment_path' => null, // Default to null
        ];

        // 3. If a file exists, store it and UPDATE the path in the data array
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $messageData['attachment_path'] = $path;
        }

        // 4. Create the message ONCE using the complete data array
        Message::create($messageData);

        // 5. Redirect back with a success message
        return redirect()->route('warden.dashboard')->with('success', 'Message sent to students successfully!');
    }
}