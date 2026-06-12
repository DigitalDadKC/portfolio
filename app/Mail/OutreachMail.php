<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OutreachMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $company, public string $email) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "✅ It's time for {$this->company} to have their own web app",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.outreach',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
