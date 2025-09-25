<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableUserDevices extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'user_agent',
        'platform',
        'resolution',
        'latitude',
        'longitude',
        'ip_address',
        'last_login',
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    public $timestamps = true; // agar created_at & updated_at otomatis
}
