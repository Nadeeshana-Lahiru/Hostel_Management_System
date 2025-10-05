<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Student;
use App\Models\Warden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnouncementEmail;

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
            'attachment' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB PDF
        ]);

        // 2. Create and save the message
        $messageData = [
            'sender_id' => Auth::id(),
            'sender_role' => 'admin',
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
        $message = Message::create($messageData);

        // 5. Get the email addresses of the recipients
        $recipientEmails = collect();

        if ($request->recipient_type === 'student_only' || $request->recipient_type === 'both') {
            $studentEmails = Student::with('user')->get()->pluck('user.email')->filter();
            $recipientEmails = $recipientEmails->merge($studentEmails);
        }

        if ($request->recipient_type === 'warden_only' || $request->recipient_type === 'both') {
            $wardenEmails = Warden::with('user')->get()->pluck('user.email')->filter();
            $recipientEmails = $recipientEmails->merge($wardenEmails);
        }

        // 6. Send the email to all recipients at once using BCC for privacy
        if ($recipientEmails->isNotEmpty()) {
            Mail::bcc($recipientEmails)->send(new AnnouncementEmail($message));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Message sent successfully!');
    }
}