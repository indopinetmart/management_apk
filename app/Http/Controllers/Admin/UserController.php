<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;

class UserController extends Controller
{


    // Halaman utama daftar pengguna
    public function index()
    {
        // Ambil semua user dengan relasi role, creator, updater
        $users = User::with(['role', 'creator', 'updater'])->get();

        // Ambil semua role, kecuali id 10 & 11 (mitra & referal)
        $roles = Role::whereNotIn('id', [10, 11])->get();

        // Mapping data users untuk ditampilkan di tabel
        $users = $users->map(function ($user, $index) {
            // Cek apakah foto user ada
            if ($user->photo && file_exists(public_path($user->photo))) {
                $photo = asset($user->photo);
            } else {
                // Jika tidak ada foto, generate avatar otomatis
                $photo = \Laravolt\Avatar\Facade::create($user->name)
                    ->setBackground('#2500F7FF')
                    ->setForeground('#FFFFFF')
                    ->toBase64();
            }

            return [
                'index'        => $index + 1,
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'photo'        => $photo,
                'role_name'    => $user->role ? $user->role->name : '-',
                'status'       => $user->status,
                'status_badge' => $user->status === 'aktif' ? 'success' : 'danger',
                'status_text'  => $user->status === 'aktif' ? 'Aktif' : 'Nonaktif',
                'is_active'    => $user->is_active,
                'created_by'   => $user->creator ? $user->creator->name : '-', // nama user yang membuat
                'updated_by'   => $user->updater ? $user->updater->name : '-', // nama user terakhir update
            ];
        });

        // Kirim data ke view
        return view('admin.users.index', compact('users', 'roles'));
    }




    public function store(Request $request)
    {
        try {
            // ✅ Validasi input user
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[A-Z]/',   // Harus ada huruf besar
                    'regex:/[0-9]/',   // Harus ada angka
                    'regex:/[\W_]/',   // Harus ada simbol
                ],
                'role_id'  => 'required|exists:roles,id',
            ], [
                'password.min'   => 'Password minimal 8 karakter',
                'password.regex' => 'Password harus mengandung huruf besar, angka, dan simbol/tanda baca',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'validation',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            // ✅ Simpan user dengan created_by dan updated_by otomatis
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'status'      => 'aktif',
                'role_id'     => $request->role_id,
                'created_by'  => Auth::id(), // siapa yang membuat
            ]);

            // ✅ Generate NIK Karyawan otomatis
            $date = date('Ymd');
            $lastProfile = UserProfile::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->first();

            $lastIncrement = $lastProfile ? intval(substr($lastProfile->nik_karyawan, -4)) + 1 : 1;

            $nikKaryawan = 'IPM-' . $date . '-' . str_pad($lastIncrement, 4, '0', STR_PAD_LEFT);

            // ✅ Simpan ke tabel user_profiles
            UserProfile::create([
                'user_id'      => $user->id,
                'nik_karyawan' => $nikKaryawan,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'User dan profil berhasil ditambahkan',
                'data'    => [
                    'user'    => $user,
                    'profile' => $nikKaryawan,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 200);
        }
    }


    /**
     * Ambil data user sebelum dihapus (untuk konfirmasi)
     */
    public function fetchDelete($id): JsonResponse
    {
        try {
            $user = User::with('role')->find($id);

            if (!$user) {
                return response()->json(['error' => 'User tidak ditemukan.'], 404);
            }

            return response()->json([
                'result' => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'role_name' => $user->role->name ?? '-',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus user dari database
     */
    public function hapusUser($id): JsonResponse
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'error' => 'User tidak ditemukan.'
                ], 404);
            }

            // langsung hapus user
            $user->delete();

            return response()->json([
                'success' => 'User berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ambil detail user untuk edit (AJAX)
     */
    public function userEditAjax(int $id): JsonResponse
    {

        $start = microtime(true);

        $user = User::with('role')->find($id);

        $end = microtime(true);
        Log::info('userEditAjax time: ' . ($end - $start) . ' sec');

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        return response()->json([
            'result' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role ? $user->role->id : null,
            ]
        ]);
    }

    /**
     * Update user (AJAX)
     */
    public function userUpdateAjax(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan.'], 404);
        }

        // Validasi email, jika email berubah, cek unique
        $emailRules = ['required', 'email'];
        if ($request->email !== $user->email) {
            $emailRules[] = 'unique:users,email';
        }

        // Validasi password, hanya jika diisi
        $passwordRules = [];
        if (!empty($request->password)) {
            $passwordRules = [
                'string',
                'min:8',
                'regex:/[A-Z]/',   // Harus ada huruf besar
                'regex:/[0-9]/',   // Harus ada angka
                'regex:/[\W_]/',   // Harus ada simbol
            ];
        }

        // Validasi semua input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => $emailRules,
            'password' => $passwordRules,
            'role_id'  => 'required|exists:roles,id',
        ], [
            'password.min'   => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, angka, dan simbol/tanda baca',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'validation',
                'message' => $validator->errors()->all()
            ]);
        }

        // Update field
        $user->name = $request->name;
        $user->email = $request->email;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        // Update role
        $role = Role::find($request->role_id);
        if (!$role) {
            return response()->json(['status' => 'error', 'message' => 'Role tidak ditemukan.'], 404);
        }
        $user->role_id = $role->id;

        // Update siapa yang mengupdate
        $user->updated_by = Auth::id();
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil diperbarui.',
            'data'    => $user
        ]);
    }


    public function nonaktif(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Contoh penggunaan data dari request
        $alasan = $request->input('reason');

        // Simpan status nonaktif
        $user->status = 'nonaktif';
        $user->updated_at = Carbon::now();
        $user->save();

        // Bisa log alasan jika perlu
        // Log::info("User ID $id dinonaktifkan karena: $alasan");

        return response()->json(['success' => 'Akun Berhasil Di Non-Aktifkan!']);
    }

    public function aktif(Request $request, $id)
    {
        // Temukan pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Contoh penggunaan data dari request
        $alasan = $request->input('reason');

        // Perbarui status pengguna menjadi non-aktif
        $user->status = 'aktif'; // Atur status menjadi '0' untuk menonaktifkan akun
        $user->updated_at = Carbon::now();
        $user->save(); // Simpan perubahan ke database

        // Kembalikan respons sukses
        return response()->json(['success' => 'Akun Berhasil Di Aktifkan!']);
    }


}
