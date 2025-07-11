<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $password) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ваш новый пароль'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user.new-password',
            with: ['password' => $this->password]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
