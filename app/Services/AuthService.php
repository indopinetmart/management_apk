<?php

namespace App\Services;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\LoginRateLimiter;
use App\Helpers\EmailVerificationHelper;
use App\Mail\SuspiciousLoginAttemptMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Events\UserLoggedIn;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Models\TableUserDevices;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthService
{
    /**
     * Handle user login
     *
     * - Validasi input login
     * - Cek RateLimiter (anti brute-force)
     * - Attempt login
     * - Simpan info device + frontend IP
     * - Simpan login attempt sukses/gagal
     * - Handle email verification
     */
    public function login(Request $request)
    {

        // ======================================================
        // 1️⃣ Validasi input login
        // ======================================================
        $validator = Validator::make($request->all(), [
            'email'       => 'required|email',
            'password'    => 'required|string',
            'remember'    => 'boolean',
            'device_id'   => 'required|string|max:255',
            'user_agent'  => 'required|string|max:255',
            'platform'    => 'required|string|max:50',
            'resolution'  => 'nullable|string|max:50',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'ip'          => 'nullable|ip', // Frontend IP
            'city'        => 'nullable|string|max:255',
            'region'      => 'nullable|string|max:255',
            'country'     => 'nullable|string|max:255',
            'org'         => 'nullable|string|max:255',
            'postal'      => 'nullable|string|max:20',
            'timezone'    => 'nullable|string|max:50',
            'browserName' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Log STEP 1 setelah validasi berhasil
        Log::info('STEP 1 - Validasi input login OK', [
            'input' => $validator->validated() // hanya field yang lolos validasi
        ]);

        // ======================================================
        // 2️⃣ Ambil user berdasarkan email (kalau ada)
        // ======================================================
        $user = User::where('email', $request->email)->first();
        Log::info('STEP 2 - Cek user dari database', ['user' => $user->id ?? null]);
        // ======================================================
        // 3️⃣ Cek RateLimiter (anti brute-force)
        // ======================================================
        if ($user) {
            $check = LoginRateLimiter::check($user);
            Log::info('STEP 3 - RateLimiter check', $check);
            if ($check['blocked']) {
                return response()->json([
                    'message' => $check['message'],
                    'lockout_seconds' => $check['lockout_seconds'] ?? 0
                ], 429);
            }
        }

        // ======================================================
        // 4️⃣ Ambil data device & frontend IP dari hasil validasi
        // ======================================================
        $validated = $validator->validated();

        // Ambil langsung dari hasil validasi
        $deviceId            = $validated['device_id'];
        $userAgent           = $validated['user_agent'] ?? $request->header('User-Agent', 'Unknown');
        $platform            = $validated['platform'];
        $resolution          = $validated['resolution'] ?? null;
        $latitude            = $validated['latitude'] ?? null;
        $longitude           = $validated['longitude'] ?? null;
        $city                = $validated['city'] ?? null;
        $region              = $validated['region'] ?? null;
        $country             = $validated['country'] ?? null;
        $org                 = $validated['org'] ?? null;
        $postal              = $validated['postal'] ?? null;
        $timezone            = $validated['timezone'] ?? null;
        $browserNameFrontend = $validated['browserName'] ?? null;

        // Ambil IP: dari validasi dulu, fallback ke header, fallback ke IP server
        $ip = $validated['ip']
            ?? ($request->header('X-Forwarded-For')
                ? trim(explode(',', $request->header('X-Forwarded-For'))[0])
                : $request->ip());

        // ======================================================
        // 5️⃣ Setup Device, Browser, dan OS Info
        // ======================================================
        $agent = new Agent();
        $agent->setUserAgent($userAgent);
        $deviceType = $agent->isMobile() ? 'Mobile' : 'PC';
        $browser    = $agent->browser() ?? 'Unknown';
        $version    = $agent->version($browser) ?? '';
        $os         = $agent->platform() ?? 'Unknown';

        // ======================================================
        // 6️⃣ Attempt Login TANPA Membuat Session Otomatis
        // ======================================================
        $credentials = $request->only('email', 'password');
        $isValid = Auth::validate($credentials);

        // ======================================================
        // 6.1 Jika login gagal → Simpan login_attempts failed
        // ======================================================
        if (!$isValid) {
            $userData = User::where('email', $credentials['email'])->first();

            Log::warning('LoginAttempt Gagal', [
                'email'      => $credentials['email'],
                'user_id'    => $userData->id ?? null,
                'success'    => 0,
                'attempted_at' => now(),
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device'     => $deviceType,
                'browser'    => $browser . ' ' . $version,
                'latitude'   => $latitude,
                'longitude'  => $longitude,
                'city'       => $city,
                'region'     => $region,
                'country'    => $country,
                'org'        => $org,
                'postal'     => $postal,
                'timezone'   => $timezone,
                'browser_name_frontend' => $browserNameFrontend,
            ]);

            LoginAttempt::create([
                'email'      => $credentials['email'],
                'user_id'    => $userData->id ?? null,
                'success'    => 0,
                'attempted_at' => now(),
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device'     => $deviceType,
                'browser'    => $browser . ' ' . $version,
                'latitude'   => $latitude,
                'longitude'  => $longitude,
                'city'       => $city,
                'region'     => $region,
                'country'    => $country,
                'org'        => $org,
                'postal'     => $postal,
                'timezone'   => $timezone,
                'browser_name_frontend' => $browserNameFrontend,
            ]);

            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // ======================================================
        // 7️⃣ Login berhasil → Ambil user
        // ======================================================
        $user = User::where('email', $credentials['email'])->first();

        // ======================================================
        // 7.1 Login manual dengan remember me
        // ======================================================
        Auth::login($user, $request->boolean('remember'));

        // ======================================================
        // 7.2 Simpan login attempt success
        // ======================================================
        LoginAttempt::create([
            'email'      => $user->email,
            'user_id'    => $user->id,
            'success'    => 1,
            'attempted_at' => now(),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'device'     => $deviceType,
            'browser'    => $browser . ' ' . $version,
            'latitude'   => $latitude,
            'longitude'  => $longitude,
            'city'       => $city,
            'region'     => $region,
            'country'    => $country,
            'org'        => $org,
            'postal'     => $postal,
            'timezone'   => $timezone,
            'browser_name_frontend' => $browserNameFrontend,
        ]);

        // ======================================================
        // 8️⃣ Update / Simpan Data Device User
        // ======================================================
        $deviceData = [
            'user_agent' => $userAgent,
            'platform'   => $platform,
            'resolution' => $resolution,
            'latitude'   => $latitude,
            'longitude'  => $longitude,
            'ip_address' => $ip,
            'last_login' => now(),
        ];

        TableUserDevices::updateOrCreate(
            [
                'user_id'   => $user->id,
                'device_id' => $deviceId,
            ],
            $deviceData
        );

        Log::info('TableUserDevices berhasil diupdate', [
            'user_id'   => $user->id,
            'device_id' => $deviceId,
            'ip'        => $ip,
            'platform'  => $platform,
            'resolution' => $resolution,
            'latitude'  => $latitude,
            'longitude' => $longitude,
        ]);


        // ======================================================
        // 5️⃣ Cek apakah email sudah diverifikasi
        // ======================================================

        if (is_null($user->email_verified_at)) {
            $rawToken = EmailVerificationHelper::createVerificationToken($user->id, $user->email);

            $verificationUrl = url('/verify-email') . '?' .
                'email=' . urlencode($user->email) .
                '&token=' . $rawToken .
                '&resolution=' . urlencode($request->input('resolution', '')) .
                '&lat=' . urlencode($request->input('latitude', '')) .
                '&long=' . urlencode($request->input('longitude', ''));

            event(new UserLoggedIn($user, $verificationUrl));

            // Logout user sampai email diverifikasi
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Silahkan Verifikasi Email Anda dan tutup Halaman Browser ini. Link verifikasi telah dikirim, ',
                'user' => [
                    'id'    => $user->id,
                    'email' => $user->email,
                ]
            ], 202);
        }

        // ======================================================
        // 🚀 Jika email SUDAH diverifikasi → lanjutkan cek token
        // ======================================================

        $frontendIp = $request->input('ip') ?: ($request->header('X-Forwarded-For') ? trim(explode(',', $request->header('X-Forwarded-For'))[0]) : $request->ip());

        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {

            if (Auth::check()) {
                $user = Auth::user();
                if (!$user) {
                    return response()->json(['message' => 'User tidak ditemukan'], 404);
                }

                $existingSession = DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', session()->getId())
                    ->first();

                if ($existingSession) {
                    $ip = $request->ip();
                    $userAgent = $request->header('User-Agent');
                    $resetUrl = route('logout.reset.page', [
                        'user' => $user->id,
                        'token' => Str::random(40)
                    ]);

                    try {
                        Mail::to($user->email)->send(new SuspiciousLoginAttemptMail(
                            $user,
                            $frontendIp,
                            $userAgent,
                            $resetUrl,
                            $platform
                        ));
                    } catch (\Exception $e) {
                        Log::error('Gagal kirim email: ' . $e->getMessage());
                    }

                    DB::table('sessions')->where('id', session()->getId())->delete();
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return response()->json([
                        'message' => 'Login ditolak, Anda sedang login di device lain. Email notifikasi telah dikirim.'
                    ], 401);
                }

                DB::table('sessions')->where('user_id', $user->id)->delete();
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'message' => 'Token tidak ditemukan di header Authorization.'
                ], 401);
            }

            return response()->json([
                'message' => 'Token tidak ditemukan di header Authorization.'
            ], 401);
        }

        // Lanjutkan proses validasi JWT/token
        $jwt = substr($authHeader, 7);
        // decode, validasi token, dan session management

        // ======================================================
        // 9️⃣ Balas ke frontend
        // ======================================================
        return response()->json([
            'message' => 'Login berhasil, selamat datang!',
            'user'    => [
                'id'    => $user->id,
                'email' => $user->email,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan atau sudah logout.'
            ], 404);
        }

        // ✅ Cek apakah user punya session aktif di tabel sessions
        $hasSession = DB::table('sessions')
            ->where('user_id', $user->id)
            ->exists();

        // ✅ Cek apakah user punya token di tabel personal_access_tokens
        $hasToken = DB::table('personal_access_tokens')
            ->where('tokenable_id', $user->id)
            ->exists();

        // Jika salah satu atau keduanya masih ada → hapus semuanya
        if ($hasSession || $hasToken) {
            if ($hasSession) {
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
            }

            if ($hasToken) {
                DB::table('personal_access_tokens')
                    ->where('tokenable_id', $user->id)
                    ->delete();
            }
        }

        // 🔴 Opsional: Reset email_verified_at supaya butuh verifikasi ulang
        $user->email_verified_at = null;
        $user->save();
        Log::info('Reset email_verified_at user', ['user_id' => $user->id]);

        // 🔴 Logout dari Laravel dan reset session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout berhasil. Semua sesi & token dihapus jika ada, dan email diverifikasi direset.'
        ], 200);
    }
}
