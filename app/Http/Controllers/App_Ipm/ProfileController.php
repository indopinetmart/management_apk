<?php

namespace App\Http\Controllers\App_Ipm;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravolt\Avatar\Facade as Avatar;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user yang sedang login
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function profile_page()
    {
        // =========================
        // Ambil user yang sedang login
        // =========================
        $user = Auth::user();

        if (!$user) {
            // Redirect ke login jika belum login
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // =========================
        // Ambil data profile tambahan dari tabel user_profiles
        // =========================
        $profile = UserProfile::where('user_id', $user->id)->first();

        // =========================
        // Tentukan foto profil utama
        // =========================
        if ($user->photo && file_exists(public_path($user->photo))) {
            $photo = asset($user->photo) . '?t=' . time();
        } else {
            // Generate avatar otomatis dengan Laravolt
            $photo = Avatar::create($user->name)
                ->setBackground('#2500F7FF')
                ->setForeground('#FFFFFF')
                ->toBase64();
        }

        // =========================
        // Mapping data user utama (users table)
        // =========================
        $userData = [
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'photo'          => $photo,
            'role_name'      => $user->role ? $user->role->name : '-', // dari relasi role
            'status'         => $user->status,
            'status_badge'   => $user->status === 'aktif' ? 'success' : 'danger',
            'status_text'    => $user->status === 'aktif' ? 'Aktif' : 'Nonaktif',
            'email_verified' => !is_null($user->email_verified_at),
        ];

        // =========================
        // Tambahan field dari tabel user_profiles
        // =========================
        $profileFields = [
            'nik_karyawan',
            'nama_lengkap',
            'no_tlp',
            'nik_ktp',
            'alamat_rumah',
            'lokasi_rumah',   // format: "latitude,longitude"
            'city_name',
            'district_name',
            'village_name',
            'province_name',
            'kodepos',
            'norek',
            'bank',
            'kontak_darurat',
            'accepted_terms',

        ];

        foreach ($profileFields as $field) {
            $userData[$field] = $profile->$field ?? null;
        }

        // =========================
        // Foto tambahan dari profile
        // =========================
        $userData['foto_ktp'] = $profile?->foto_ktp ? asset('assets/images/ktp/' . $profile->foto_ktp) . '?t=' . time() : null;
        $userData['foto_tambahan'] = $profile?->foto ? asset('assets/images/foto/' . $profile->foto) . '?t=' . time() : null;
        $userData['verifikasi_muka'] = $profile?->verifikasi_muka ? asset('assets/images/verifikasi/' . $profile->verifikasi_muka) . '?t=' . time() : null;
        $userData['created_at'] = $profile?->created_at ? $profile->created_at->format('Y-m-d H:i:s') : null;

        // =========================
        // Validasi otomatis semua field (valid / warning / error)
        // =========================
        $status = [];

        // ----- Users table -----
        if (empty($userData['name'])) {
            $status['name'] = 'error';
        } elseif (strlen($userData['name']) < 3) {
            $status['name'] = 'warning';
        } else {
            $status['name'] = 'valid';
        }

        // ----- Email-----
        if (empty($userData['email'])) {
            $status['email'] = 'error';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $status['email'] = 'warning';
        } else {
            $status['email'] = 'valid';
        }

        $status['email_verified'] = $userData['email_verified'] ? 'valid' : 'warning';

        $status['status'] = in_array($userData['status'], ['aktif', 'nonaktif']) ? 'valid' : 'warning';

        // ----- UserProfile table -----
        $status['nama_lengkap'] = !empty($userData['nama_lengkap'])
            ? (strlen($userData['nama_lengkap']) < 5 ? 'warning' : 'valid')
            : 'error';

        $status['nik_karyawan'] = !empty($userData['nik_karyawan'])
            ? (strlen($userData['nik_karyawan']) < 5 ? 'warning' : 'valid')
            : 'error';

        $status['no_tlp'] = !empty($userData['no_tlp'])
            ? (preg_match('/^\+?\d{10,15}$/', $userData['no_tlp']) ? 'valid' : 'warning')
            : 'error';

        $status['nik_ktp'] = !empty($userData['nik_ktp'])
            ? (preg_match('/^\d{16,20}$/', $userData['nik_ktp']) ? 'valid' : 'warning')
            : 'error';

        $status['alamat_rumah'] = !empty($userData['alamat_rumah']) ? 'valid' : 'error';
        $status['lokasi_rumah'] = !empty($userData['lokasi_rumah']) ? 'valid' : 'warning';

        $status['city_name'] = !empty($userData['city_name']) ? 'valid' : 'warning';
        $status['district_name'] = !empty($userData['district_name']) ? 'valid' : 'warning';
        $status['village_name'] = !empty($userData['village_name']) ? 'valid' : 'warning';
        $status['province_name'] = !empty($userData['province_name']) ? 'valid' : 'warning';
        $status['kodepos'] = !empty($userData['kodepos']) && preg_match('/^\d{5,10}$/', $userData['kodepos']) ? 'valid' : 'warning';

        $status['norek'] = !empty($userData['norek']) ? 'valid' : 'warning';
        $status['bank'] = !empty($userData['bank']) ? 'valid' : 'warning';
        $status['kontak_darurat'] = !empty($userData['kontak_darurat']) ? 'valid' : 'warning';
        $status['accepted_terms'] = $userData['accepted_terms'] ? 'valid' : 'warning';

        $status['foto_ktp'] = !empty($userData['foto_ktp']) ? 'valid' : 'warning';
        $status['foto_tambahan'] = !empty($userData['foto_tambahan']) ? 'valid' : 'warning';
        $status['verifikasi_muka'] = !empty($userData['verifikasi_muka']) ? 'valid' : 'warning';
        $status['created_at'] = !empty($userData['created_at']) ? 'valid' : 'warning';

        // =========================
        // Pisahkan latitude dan longitude dari lokasi_rumah
        // =========================
        $latitude = null;
        $longitude = null;

        if (!empty($userData['lokasi_rumah']) && strpos($userData['lokasi_rumah'], ',') !== false) {
            [$latitude, $longitude] = explode(',', $userData['lokasi_rumah']);
            $latitude = trim($latitude);
            $longitude = trim($longitude);
        }

        // =========================
        // Kirim data ke view
        // =========================
        return view('admin.profile.index', compact('userData', 'status', 'latitude', 'longitude'));
    }


    public function updatePhoto(Request $request)
    {
        // 🔹 Validasi file
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:4096', // max 4MB
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $ext  = $file->getClientOriginalExtension();

            // 🔹 Path tujuan
            $destination = public_path('assets/images/profile');

            // 🔹 Buat folder kalau belum ada
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
                Log::info("📂 Folder dibuat: {$destination}");
            }

            // 🔥 Hapus foto lama kalau ada dan bukan default
            if ($user->photo && $user->photo !== 'assets/images/default.png') {
                $oldPhotoPath = public_path($user->photo);
                try {
                    if (File::exists($oldPhotoPath)) {
                        File::delete($oldPhotoPath);
                        Log::info("✅ Foto lama berhasil dihapus: {$oldPhotoPath}");
                    } else {
                        Log::warning("⚠️ Foto lama tidak ditemukan: {$oldPhotoPath}");
                    }
                } catch (\Exception $e) {
                    Log::error("❌ Gagal hapus foto lama: {$oldPhotoPath}, error: " . $e->getMessage());
                }
            }

            // 🔹 Generate nama file unik
            $safeUsername = Str::slug($user->name ?? 'user', '_');
            $filename = $user->id . '_' . $safeUsername . '_' . time() . '.' . $ext;

            // 🔹 Pindahkan file ke folder tujuan
            $file->move($destination, $filename);
            Log::info("📸 Foto baru disimpan: {$destination}/{$filename}");

            // 🔹 Update database
            $user->photo = 'assets/images/profile/' . $filename;
            $user->save();
            Log::info("💾 DB user diperbarui. ID: {$user->id}, Foto: {$user->photo}");

            return response()->json([
                'success'   => true,
                'photo_url' => asset($user->photo),
                'message'   => 'Foto profil berhasil diupdate.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file foto yang diupload.'
        ], 400);
    }
}
