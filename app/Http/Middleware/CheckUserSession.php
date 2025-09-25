<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserSession
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return $this->sessionExpiredResponse();
        }

        $user = Auth::user();

        // ✅ 2. Cek apakah session user masih ada di tabel "sessions"
        $hasSession = DB::table('sessions')
            ->where('user_id', $user->id)
            ->exists();

        // ✅ 3. Jika session sudah hilang → logout paksa dan arahkan ke expired
        if (!$hasSession) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->sessionExpiredResponse();
        }

        // ✅ 4. Jika user & session valid → lanjutkan request
        return $next($request);
    }

    /**
     * Response jika session sudah expired atau user belum login.
     */
    private function sessionExpiredResponse()
    {
        return response()->view('errors.session_expired', [], 419);
    }
}
