<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SessionController extends Controller
{
    /**
     * API: Hapus semua sesi dan kembalikan JSON.
     */
    public function reset(Request $request)
    {
        $userId = $request->query('user');
        $token = $request->query('token');

        // ⚠️ Validasi token
        if (!$userId || !$token) {
            abort(403, 'Link tidak valid.');
        }

        DB::table('sessions')->where('user_id', $userId)->delete();

        return response()->json([
            'message' => 'Semua sesi berhasil dihapus. Silakan login ulang dan segera ganti password.'
        ]);
    }

    public function logoutResetPage(Request $request)
    {
        $userId = $request->query('user');

        // Cari user berdasarkan ID
        $user = DB::table('users')->where('id', $userId)->first();

        if ($user) {
            // Hapus semua sesi user
            DB::table('sessions')->where('user_id', $user->id)->delete();

            // Hapus semua token Sanctum/Passport user
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();

            // Reset email_verified_at
            DB::table('users')->where('id', $user->id)->update([
                'email_verified_at' => null
            ]);
        }

        // Tidak perlu Auth::logout() karena ini dipanggil lewat email (bukan session aktif)
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('auth.logout_reset', [
            'message' => 'Semua sesi & token berhasil direset. Silakan login ulang.'
        ]);
    }
}
