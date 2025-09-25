<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\EmailVerificationHelper;
use App\Models\User;
use App\Models\TableUserDevices;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VerifyEmailController extends Controller
{
    /**
     * Render halaman verify-email
     */
    public function showPage()
    {
        return view('auth.verify-email');
    }

    /**
     * Verifikasi email user
     */
    public function verify(Request $request)
    {
        // Ambil query parameter email & token
        $email = $request->input('email', $request->query('email'));
        $token = $request->input('token', $request->query('token'));

        if (!$email || !$token) {
            return response()->json(['success' => false, 'message' => 'Link verifikasi tidak valid.'], 422);
        }

        // Cari user
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 403);
        }

        // Verifikasi token
        $record = EmailVerificationHelper::verifyToken($email, $token);
        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Token verifikasi tidak valid atau kadaluarsa.'], 401);
        }

        // Tandai email verified
        $user->email_verified_at = now();
        $user->save();

        // Hapus token lama
        $record->delete();

        // Generate JWT
        $tokenData = EmailVerificationHelper::generatePersonalAccessToken($user);

        // Simpan hashed JWT ke DB (penting untuk AuthorizeController)
        DB::table('personal_access_tokens')->updateOrInsert(
            ['tokenable_id' => $user->id, 'tokenable_type' => User::class],
            [
                'token'      => hash('sha256', $tokenData['jwt']), // 🔹 HASH
                'name'       => 'default',
                'abilities'  => json_encode(['*']),
                'expires_at' => now()->addHours(48),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Ambil device info
        $deviceId  = $request->input('device_id', 'unknown');
        $userAgent = $request->input('user_agent', $request->header('User-Agent', 'unknown'));

        // Ambil long, lat, resolution dari query dulu
        $resolution = $request->query('resolution') ?? $request->input('resolution', null);
        $latitude   = $request->input('lat', $request->query('lat', null));
        $longitude  = $request->input('long', $request->query('long', null));

        // Analisa device/browser
        $agent = new Agent();
        $agent->setUserAgent($userAgent);
        $deviceType = $agent->isMobile() ? 'Mobile' : 'PC';
        $browser    = $agent->browser() ?? 'Unknown';

        // Simpan / update device user
        TableUserDevices::updateOrCreate(
            ['user_id' => $user->id, 'device_id' => $deviceId],
            [
                'platform'   => $deviceType,
                'browser'    => $browser,
                'user_agent' => $userAgent,
                'resolution' => $resolution, // ✅ disimpan
                'ip_address' => $request->ip(),
                'latitude'   => $latitude,   // ✅ disimpan
                'longitude'  => $longitude,  // ✅ disimpan
                'last_login' => now(),
            ]
        );

        // Catat login attempt
        LoginAttempt::create([
            'email'        => $user->email,
            'user_id'      => $user->id,
            'success'      => 1,
            'attempted_at' => now(),
            'ip_address'   => $request->ip(),
            'user_agent'   => $userAgent,
            'device'       => $deviceType,
            'browser'      => $browser,
            'latitude'     => $latitude,
            'longitude'    => $longitude,
        ]);

        Auth::login($user);
        session()->regenerate();

        // Return response JSON
        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diverifikasi dan login berhasil!',
            'data' => [
                'user'       => ['id' => $user->id, 'email' => $user->email],
                'token'      => $tokenData['jwt'],
                'expires_at' => $tokenData['expireAt'],
            ]
        ], 200);
    }
}
