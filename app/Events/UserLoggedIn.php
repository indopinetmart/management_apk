<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserLoggedIn
{
    use SerializesModels;

    /**
     * User yang login
     *
     * @var User
     */
    public $user;

    /**
     * Full URL verifikasi email (sudah mengandung token mentah)
     *
     * @var string
     */
    public $verificationUrl;

    /**
     * Buat event baru.
     *
     * @param User $user
     * @param string $verificationUrl URL lengkap untuk verifikasi email
     */
    public function __construct(User $user, string $verificationUrl)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
    }
}
