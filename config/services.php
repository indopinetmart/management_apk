<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | File ini menyimpan kredensial untuk service pihak ketiga seperti
    | Mailgun, Postmark, AWS, Nominatim, dll. Dengan cara ini, paket bisa
    | menggunakan file konvensional untuk mencari konfigurasi.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'nominatim' => [
        'url'        => env('NOMINATIM_URL', 'https://nominatim.openstreetmap.org/reverse'),
        'user_agent' => env('NOMINATIM_USER_AGENT', 'MyApp/1.0 (email@example.com)'),
        'timeout'    => env('NOMINATIM_TIMEOUT', 10),
    ],

    'rajaongkir' => [
        'base_url' => env('RAJAONGKIR_BASE_URL'),
        'api_key_cost' => env('RAJAONGKIR_API_KEY_COST'),
        'api_key_delivery' => env('RAJAONGKIR_API_KEY_DELIVERY'),
    ],

    'datawilayah' => [
        'base_url' => env('DATAWILAYAH_BASE_URL', 'https://api.datawilayah.com/api'),
    ],



];
