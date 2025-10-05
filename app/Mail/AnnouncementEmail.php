<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class AnnouncementEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $announcement; 

    /**
     * Create a new message instance.
     */
    public function __construct(Message $message)
    {
        $this->announcement = $message; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->announcement->title, // Use the message title as the email subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement', // The email's HTML template
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // If the announcement has a PDF, attach it to the email
        if ($this->announcement->attachment_path) { // <-- RENAMED from $this->message
            return [
                Attachment::fromStorageDisk('public', $this->announcement->attachment_path),
            ];
        }

        return [];
    }
}