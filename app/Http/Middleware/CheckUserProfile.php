<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;

class CheckUserProfile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        if ($request->routeIs('profile.page')) {
            return $next($request); // 🚀 jangan cek kalau sudah di halaman profile
        }

        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return redirect()->route('profile.page')->with('warning', 'Lengkapi profil Anda terlebih dahulu.');
        }

        $requiredFields = [
            'nama_lengkap',
            'no_tlp',
            'nik_ktp',
            'foto_ktp',
            'alamat_rumah',
            'lokasi_rumah',
            'kota',
            'kabupaten',
            'norek',
            'bank',
            'foto',
            'verifikasi_muka',
            'kontak_darurat'
        ];

        foreach ($requiredFields as $field) {
            if (is_null($profile->$field) || $profile->$field === '') {
                return redirect()->route('profile.page')->with('warning', 'Lengkapi profil Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
