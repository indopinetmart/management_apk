<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\LoginAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class LoginRateLimiter
{
    // 🔹 Konstanta aturan rate limit
    const BLOCK_ATTEMPTS     = 3;     // Blok sementara (30 menit)
    const SUSPEND_ATTEMPTS   = 6;     // Suspend (12 jam)
    const NONACTIVE_ATTEMPTS = 9;     // Nonaktif
    const BLOCK_MINUTES      = 30;
    const SUSPEND_HOURS      = 12;

    /**
     * 🔹 Cek status login user
     *
     * @param User $user
     * @return array ['blocked' => bool, 'message' => string|null, 'lockout_seconds' => int|null]
     */
    public static function check(User $user)
    {
        $now = Carbon::now();

        // Ambil semua percobaan login gagal 24 jam terakhir
        $failedAttempts = LoginAttempt::where('email', $user->email)
            ->where('success', 0)
            ->where('attempted_at', '>=', $now->copy()->subDay())
            ->orderByDesc('attempted_at')
            ->get();

        $failedCount = $failedAttempts->count();

        // =========================
        // 1️⃣ Suspend user jika status = 'suspend'
        // =========================
        if ($user->status === 'suspend') {
            $suspendAt = $user->suspend_at ?? $failedAttempts->first()?->attempted_at;

            if ($suspendAt) {
                $suspendUntil = Carbon::parse($suspendAt)->addHours(self::SUSPEND_HOURS);
                $remainingSeconds = $now->diffInSeconds($suspendUntil, false);

                if ($remainingSeconds > 0) {
                    return [
                        'blocked' => true,
                        'message' => "Akun Anda ditangguhkan sementara.",
                        'lockout_seconds' => (int) $remainingSeconds
                    ];
                } else {
                    $user->status = 'aktif';
                    $user->suspend_at = null;
                    $user->save();
                }
            }
        }

        // =========================
        // 2️⃣ Nonaktif user
        // =========================
        if ($user->status === 'nonaktif') {
            return [
                'blocked' => true,
                'message' => "Akun Anda dinonaktifkan. Hubungi admin.",
                'lockout_seconds' => null
            ];
        }

        // =========================
        // 3️⃣ Nonaktif otomatis 9 kali gagal
        // =========================
        if ($failedCount >= self::NONACTIVE_ATTEMPTS) {
            $user->status = 'nonaktif';
            $user->save();

            Mail::raw(
                "Akun Anda telah dinonaktifkan karena " . self::NONACTIVE_ATTEMPTS . " kali gagal login dalam 24 jam.",
                function ($msg) use ($user) {
                    $msg->to($user->email)->subject("Akun Dinonaktifkan");
                }
            );

            return [
                'blocked' => true,
                'message' => "Akun Anda dinonaktifkan karena terlalu banyak percobaan login.",
                'lockout_seconds' => null
            ];
        }

        // =========================
        // 4️⃣ Suspend otomatis 6 kali gagal
        // =========================
        if ($failedCount >= self::SUSPEND_ATTEMPTS) {
            $user->status = 'suspend';
            $user->suspend_at = now();
            $user->save();

            return [
                'blocked' => true,
                'message' => "Akun Anda ditangguhkan selama " . self::SUSPEND_HOURS . " jam.",
                'lockout_seconds' => self::SUSPEND_HOURS * 3600
            ];
        }

        // =========================
        // 5️⃣ Blok sementara 3 kali gagal
        // =========================
        if ($failedCount >= self::BLOCK_ATTEMPTS) {
            $thirdLastAttempt = $failedAttempts->take(self::BLOCK_ATTEMPTS)->last();
            if ($thirdLastAttempt) {
                $attemptTime = Carbon::parse($thirdLastAttempt->attempted_at);
                $blockUntil = $attemptTime->copy()->addMinutes(self::BLOCK_MINUTES);
                $remainingSeconds = $now->diffInSeconds($blockUntil, false);

                if ($remainingSeconds > 0) {
                    return [
                        'blocked' => true,
                        'message' => "Terlalu banyak percobaan login gagal. Coba lagi nanti.",
                        'lockout_seconds' => (int) $remainingSeconds
                    ];
                }
            }
        }

        // ✅ Tidak diblok
        return [
            'blocked' => false,
            'message' => null,
            'lockout_seconds' => null
        ];
    }
}
