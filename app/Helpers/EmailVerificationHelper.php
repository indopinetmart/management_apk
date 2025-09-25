<?php

namespace App\Helpers;

use App\Models\EmailVerification;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EmailVerificationHelper
{
    /**
     * 🔹 Generate token baru & simpan hash ke DB
     */
    public static function createVerificationToken($userId, $email)
    {
        // hapus token lama
        EmailVerification::where('email', $email)->delete();

        // generate raw token
        $rawToken = Str::uuid()->toString();

        // simpan hash token ke DB
        EmailVerification::create([
            'user_id'    => $userId,
            'email'      => $email,
            'token'      => hash('sha256', $rawToken), // hash untuk keamanan
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);

        return $rawToken; // dikirim ke user via email
    }

    /**
     * 🔹 Verifikasi token email
     *
     * 1️⃣ Ambil record email yang belum expired
     * 2️⃣ Cocokkan hash DB dengan hash dari raw token input
     * 3️⃣ Return record jika valid, atau false jika expired / salah token
     */
    public static function verifyToken($email, $rawToken)
    {
        // ambil token terakhir yang belum expired
        $record = EmailVerification::where('email', $email)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$record) {
            return false; // token expired atau tidak ada
        }

        // cocokkan hash
        return hash('sha256', $rawToken) === $record->token ? $record : false;
    }

    /**
     * 🔹 Generate personal_access_token  & simpan hash ke DB
     */

    public static function generatePersonalAccessToken($user, $name = 'default')
    {
        $privateKey = file_get_contents(storage_path('keys/privateKey.pem'));

        $issuedAt = time();
        $expireAt = $issuedAt + (60 * 60 * 48);

        $payload = [
            'iss'   => config('app.url'),
            'aud'   => 'pinetmart_app',
            'sub'   => $user->id,
            'email' => $user->email,
            'iat'   => $issuedAt,
            'exp'   => $expireAt,
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        $user->personalAccessTokens()->create([
            'name'       => $name,
            'token'      => $jwt,
            'abilities'  => ['*'],
            'expires_at' => Carbon::createFromTimestamp($expireAt),
        ]);

        return [
            'jwt'      => $jwt,
            'expireAt' => Carbon::createFromTimestamp($expireAt),
        ];
    }

    /**
     * 🔹 Decode dan validasi JWT
     */
    public static function decodePersonalAccessToken($token)
    {
        $publicKey = file_get_contents(storage_path('keys/publicKey.pem'));

        try {
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));

            if ($decoded->iss !== config('app.url')) {
                throw new \Exception("Invalid issuer");
            }
            if ($decoded->aud !== 'pinetmart_app') {
                throw new \Exception("Invalid audience");
            }

            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return 'expired';
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return 'invalid';
        } catch (\Exception $e) {
            return null;
        }
    }
}
