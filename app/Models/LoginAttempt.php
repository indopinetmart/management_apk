<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'city',
        'region',
        'country',
        'org',
        'postal',
        'timezone',
        'success',
        'user_agent',
        'device',
        'browser',
        'browser_name_frontend',
        'latitude',
        'longitude',
        'attempted_at',
    ];

    public $timestamps = false; // karena pakai attempted_at manual

    /**
     * Relasi ke User (satu attempt dimiliki oleh satu user).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
