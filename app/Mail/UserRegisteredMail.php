<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $name) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Добро пожаловать!'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user.registered',
            with: ['name' => $this->name]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
