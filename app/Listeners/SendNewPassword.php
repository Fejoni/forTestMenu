<?php

namespace App\Listeners;

use App\Mail\NewPasswordMail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendNewPassword
{
    public function handle(PasswordReset $event): void
    {
        $newPassword = Str::random(12);

        $event->user->forceFill([
            'password' => Hash::make($newPassword),
        ])->save();

        Mail::to($event->user->email)->send(new NewPasswordMail($newPassword));
    }
}
