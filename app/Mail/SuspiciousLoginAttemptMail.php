<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuspiciousLoginAttemptMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $user;
    public $frontendIp;
    public $userAgent;
    public $resetUrl;
    public $platform;


    /**
     * Create a new message instance.
     */
    public function __construct($user, $frontendIp, $userAgent, $resetUrl, $platform)
    {
        $this->user = $user;
        $this->frontendIp = $frontendIp;
        $this->userAgent = $userAgent;
        $this->resetUrl = $resetUrl;
        $this->platform = $platform;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('⚠️ Percobaan Login Mencurigakan di Akun Anda')
            ->view('emails.suspicious_login');
    }
}
