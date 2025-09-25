<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthorizeController extends Controller
{
    /**
     * 🔹 Cek validitas token JWT dan simpan session dengan IP & device terbaru
     */
    public function check(Request $request)
    {
        // 1️⃣ Ambil header Authorization
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan'
            ], 401);
        }

        // 2️⃣ Ambil token asli
        $jwt = substr($authHeader, 7);
        $hashedToken = hash('sha256', $jwt);

        // 3️⃣ Cari token di DB dan cek expire
        $tokenRecord = DB::table('personal_access_tokens')
            ->where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->first();

        if (!$tokenRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau kadaluarsa'
            ], 401);
        }

        // 4️⃣ Ambil user
        $user = User::find($tokenRecord->tokenable_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        // 5️⃣ Ambil login_attempt terakhir (success=1)
        $attemptRecord = DB::table('login_attempts')
            ->where('user_id', $user->id)
            ->where('success', 1)
            ->orderByDesc('attempted_at') // urut dari terbaru ke lama
            ->skip(1)                     // lewati yang terbaru
            ->first();

        // 6️⃣ Ambil device terbaru dari device_id login_attempt jika ada
        $deviceRecord = DB::table('table_user_devices')
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->skip(1)
            ->first();



        // 9️⃣ Insert / Update sessions aman untuk MySQL
        DB::table('sessions')->updateOrInsert(
            ['id' => $hashedToken],
            [
                'user_id'       => $user->id,
                'device_id'     => $deviceRecord->device_id,
                'device_type'   =>$attemptRecord->device,
                'browser'       =>$attemptRecord->browser,
                'platform'      => $deviceRecord->platform,
                'resolution'    => $deviceRecord->resolution,
                'ip_address'    => $attemptRecord?->ip_address,
                'user_agent'    => $attemptRecord->browser_name_frontend,
                'latitude'      => $attemptRecord->latitude,
                'longitude'     => $attemptRecord->longitude,
                'city'          => $attemptRecord?->city,
                'region'        => $attemptRecord?->region,
                'country'       => $attemptRecord?->country,
                'org'           => $attemptRecord?->org,
                'timezone'      => $attemptRecord?->timezone,
                'payload'       => serialize(['_token' => $jwt]),
                'last_activity' => time(),
            ]
        );

        // 10️⃣ Response sukses
        return response()->json([
            'success' => true,
            'message' => 'Token valid, user terotorisasi',
            'user'    => [
                'id'    => $user->id,
                'email' => $user->email,
            ]
        ], 200);
    }
}
