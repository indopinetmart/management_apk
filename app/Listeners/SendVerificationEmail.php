<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;

class SendVerificationEmail
{
    public function handle(UserLoggedIn $event)
    {
        // Gunakan properti yang benar: $verificationUrl
        Mail::to($event->user->email)->send(
            new VerifyEmailMail($event->user, $event->verificationUrl)
        );
    }
}
