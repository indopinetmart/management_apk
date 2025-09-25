<?php

namespace App\Http\Controllers\App_Ipm;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // ✅ tambahkan ini
use Illuminate\Support\Facades\Log;      // ✅ tambahkan ini
use Illuminate\Support\Facades\Http;
use App\Services\FaceRecognitionService;
use Illuminate\Http\JsonResponse;
use App\Services\DataWilayahService;

// use App\Services\RajaOngkirService;



class AppController extends Controller
{
    public function app_ipm(DataWilayahService $dataWilayah)
    {
        $user = Auth::user();

        // Ambil profile user
        $profile = UserProfile::where('user_id', $user->id)->first();

        // Jika profile belum ada, buat baru
        if (!$profile) {
            $profile = UserProfile::create([
                'user_id' => $user->id,
                'accepted_terms' => 0, // Terms belum disetujui
            ]);
        }

        // Tentukan URL foto selfie (jika ada)
        $selfieUrl = ($profile->foto && file_exists(public_path($profile->foto)))
            ? asset($profile->foto)
            : null;

        // Modal persetujuan layanan
        $showTermsModal = ($profile->accepted_terms == 0);

        // Modal lengkapi profil
        $showCompleteProfileModal = $profile->accepted_terms == 1 &&
            (!$profile->nik_karyawan || !$profile->nama_lengkap || !$profile->no_tlp || !$profile->nik_ktp ||
                !$profile->alamat_rumah || !$profile->province_name || !$profile->city_name || !$profile->norek || !$profile->bank || !$profile->kontak_darurat ||
                !$profile->district_name || !$profile->village_name || !$profile->kodepos);

        // ==============================
        // 🔹 Data Wilayah
        // ==============================
        $provinces = $dataWilayah->getProvinces() ?? [];

        // dd($provinces);

        $cities = !empty($profile->province_id)
            ? $dataWilayah->getCities($profile->province_id) ?? []
            : [];

        $districts = !empty($profile->city_id)
            ? $dataWilayah->getDistricts($profile->city_id) ?? []
            : [];

        $villages = !empty($profile->district_id)
            ? $dataWilayah->getVillages($profile->district_id) ?? []
            : [];

        // ==============================
        // 🔹 Kirim ke view
        // ==============================
        return view('index', compact(
            'user',
            'profile',
            'selfieUrl',
            'showTermsModal',
            'showCompleteProfileModal',
            'provinces',
            'cities',
            'districts',
            'villages'
        ));
    }


    /**
     * 📌 Ambil daftar provinsi
     */
    public function getProvinces(DataWilayahService $dataWilayah)
    {
        $provinces = $dataWilayah->getProvinces();

        $result = collect($provinces)->map(function ($province) {
            return [
                'id'   => $province['id'],
                'name' => $province['name'],
            ];
        })->toArray();

        return response()->json(['data' => $result]);
    }

    /**
     * 📌 Ambil daftar kota/kabupaten berdasarkan province_id
     */
    public function getCities($province_id, DataWilayahService $dataWilayah)
    {
        $cities = $dataWilayah->getCities($province_id);

        $result = collect($cities)->map(function ($city) {
            return [
                'id'   => $city['id'],
                'name' => $city['name'],
                'type' => '',
                'zip'  => null,
            ];
        })->toArray();

        return response()->json(['data' => $result]);
    }

    /**
     * 📌 Ambil daftar kecamatan berdasarkan city_id
     */
    public function getDistricts($city_id, DataWilayahService $dataWilayah)
    {
        $districts = $dataWilayah->getDistricts($city_id);

        $result = collect($districts)->map(function ($district) {
            return [
                'id'   => $district['id'],
                'name' => $district['name'],
                'zip'  => null,
            ];
        })->toArray();

        return response()->json(['data' => $result]);
    }

    /**
     * 📌 Ambil daftar kelurahan berdasarkan district_id
     */
    public function getVillages($district_id, DataWilayahService $dataWilayah)
    {
        $villages = $dataWilayah->getVillages($district_id);

        $result = collect($villages)->map(function ($village) {
            return [
                'id'       => $village['id'],
                'name'     => $village['name'],
                'zip_code' => $village['zip_code'] ?? null,
            ];
        })->toArray();

        return response()->json(['data' => $result]);
    }



    /**
     * Simpan persetujuan Terms & Conditions
     * - Mengupdate field accepted_terms menjadi 1
     * - Return JSON success/error
     */
    public function acceptTerms(Request $request)
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json([
                'type' => 'error',
                'message' => 'Profile tidak ditemukan',
                'status' => 404
            ], 404);
        }

        $profile->accepted_terms = 1;
        $profile->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Persetujuan layanan berhasil disimpan',
            'status' => 200
        ], 200);
    }


    /**
     * Simpan / Update profile user
     * - Validasi input manual untuk return JSON error
     * - Menyimpan province_id, city_id, dan kodepos
     * - Cek apakah data verifikasi lengkap (foto ktp, selfie, lokasi, verifikasi wajah)
     */
    public function saveProfile(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'User tidak ditemukan',
                    'status' => 404
                ], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'nik_karyawan'   => 'nullable|string|max:50',
                'nik_ktp'        => 'required|string|max:50',
                'nama_lengkap'   => 'required|string|max:150',
                'no_tlp'         => 'required|string|max:20',
                'alamat_rumah'   => 'required|string|max:255',
                'province_name'  => 'required|string|max:100',
                'city_name'      => 'required|string|max:100',
                'district_name'  => 'required|string|max:100',
                'village_name'   => 'required|string|max:100',
                'kodepos'        => 'required|string|max:10',
                'bank'           => 'required|string|max:100',
                'norek'          => 'required|string|max:50',
                'kontakdarurat'  => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                    'status' => 422
                ], 422);
            }

            // Ambil atau buat profile
            $profile = UserProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['accepted_terms' => 1]
            );

            // Update semua field sesuai database
            $profile->update([
                'nik_ktp'        => $request->nik_ktp,
                'nama_lengkap'   => $request->nama_lengkap,
                'no_tlp'         => $request->no_tlp,
                'alamat_rumah'   => $request->alamat_rumah,
                'province_name'  => $request->province_name,
                'city_name'      => $request->city_name,
                'district_name'  => $request->district_name,
                'village_name'   => $request->village_name,
                'kodepos'        => $request->kodepos,
                'bank'           => $request->bank,
                'norek'          => $request->norek,
                'kontak_darurat' => $request->kontakdarurat,
            ]);

            $profile->refresh();

            // Cek apakah verifikasi lengkap
            $needVerification = empty($profile->foto_ktp)
                || empty($profile->lokasi_rumah)
                || empty($profile->foto)
                || empty($profile->verifikasi_muka);

            return response()->json([
                'type' => 'success',
                'message' => 'Profil berhasil disimpan',
                'status' => 200,
                'need_verification' => $needVerification
            ], 200);
        } catch (\Exception $e) {
            Log::error('Save Profile Error: ' . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => 'Terjadi kesalahan server',
                'status' => 500
            ], 500);
        }
    }

    /**
     * Cek status verifikasi user
     * - Mengecek apakah field wajib (foto ktp, selfie, lokasi, verifikasi wajah) sudah lengkap
     * - Menentukan step berikutnya untuk user
     * - Return JSON berisi status verifikasi
     */
    public function checkVerification(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'User tidak ditemukan',
                    'status' => 404
                ], 404);
            }

            $profile = $user->profile;
            if (!$profile) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Profil belum dibuat',
                    'status' => 404
                ], 404);
            }

            // Cek field yang wajib
            $missingFields = [];
            if (empty($profile->foto_ktp)) {
                $missingFields[] = 'Foto KTP';
            }
            if (empty($profile->lokasi_rumah)) {
                $missingFields[] = 'Lokasi Rumah';
            }
            if (empty($profile->foto)) {
                $missingFields[] = 'Foto Selfie';
            }
            if (empty($profile->verifikasi_muka)) {
                $missingFields[] = 'Verifikasi Wajah';
            }

            // Tentukan step berikutnya
            $nextStep = null;
            if (empty($profile->foto_ktp)) {
                $nextStep = 'ktp';
            } elseif (empty($profile->lokasi_rumah)) {
                $nextStep = 'lokasi';
            } elseif (empty($profile->foto)) {
                $nextStep = 'selfie';
            } elseif (empty($profile->verifikasi_muka)) {
                $nextStep = 'verifikasi_muka';
            }

            return response()->json([
                'type' => 'success',
                'status' => 200,
                'need_verification' => count($missingFields) > 0,
                'missing_fields' => $missingFields,
                'next_step' => $nextStep
            ]);
        } catch (\Exception $e) {
            Log::error('Check Verification Error: ' . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => 'Terjadi kesalahan server',
                'status' => 500
            ], 500);
        }
    }



    public function uploadKTP(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi.'
                ], 401);
            }

            $imageData = $request->input('image');
            if (!$imageData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data gambar.'
                ], 400);
            }

            // decode base64
            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $image = base64_decode($imageData);

            // generate nama file
            $unique = uniqid();
            $fileName = "ktp_" . $user->id . "_" . $user->name . "_" . $unique . ".png";
            $relativePath = 'assets/images/ktp/' . $fileName;
            $path = public_path($relativePath);

            // pastikan folder ada
            if (!file_exists(public_path('assets/images/ktp'))) {
                mkdir(public_path('assets/images/ktp'), 0777, true);
            }

            // simpan file
            file_put_contents($path, $image);

            // simpan ke user_profiles
            $profile = $user->profile; // relasi hasOne
            if (!$profile) {
                $profile = new \App\Models\UserProfile();
                $profile->user_id = $user->id;
            }
            $profile->foto_ktp = $relativePath;
            $profile->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto KTP berhasil disimpan.',
                'file' => $relativePath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadLokasi(Request $request)
    {
        try {
            // 1. Validasi input
            $validated = $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            // 2. Cek user login
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi.'
                ], 401);
            }

            // 3. Ambil atau buat profile user
            $profile = $user->profile;
            if (!$profile) {
                $profile = new UserProfile();
                $profile->user_id = $user->id;
            }

            // 4. Simpan lokasi ke kolom profile
            $profile->lokasi_rumah = $validated['latitude'] . ',' . $validated['longitude'];
            $profile->save();

            // 5. Response sukses
            return response()->json([
                'success' => true,
                'message' => 'Lokasi rumah berhasil disimpan.',
                'data'    => [
                    'latitude'  => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'lokasi'    => $profile->lokasi_rumah,
                ]
            ], 200);
        } catch (\Throwable $e) {
            // 6. Tangkap error tak terduga
            Log::error('Upload Lokasi Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    public function showLokasi()
    {
        /** @var User $user */
        $user = Auth::user();

        // 🔒 Cek apakah user login
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Harap login terlebih dahulu.');
        }

        // 🔹 Ambil lokasi terakhir dari login_attempts
        $lokasiLogin = DB::table('login_attempts')
            ->where('user_id', $user->id)
            ->orderByDesc('attempted_at')
            ->select('latitude', 'longitude', 'attempted_at')
            ->first();

        // Cast koordinat ke float supaya Leaflet/Maps aman
        $latitude  = $lokasiLogin ? (float) $lokasiLogin->latitude : null;
        $longitude = $lokasiLogin ? (float) $lokasiLogin->longitude : null;
        $alamatLogin = null;

        // 🔹 Reverse geocoding via Nominatim
        if ($latitude && $longitude) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => env('NOMINATIM_USER_AGENT', 'MyApp/1.0 (myapp@example.com)'),
                ])
                    ->timeout(env('NOMINATIM_TIMEOUT', 10))
                    ->get(env('NOMINATIM_URL', 'https://nominatim.openstreetmap.org/reverse'), [
                        'lat' => $latitude,
                        'lon' => $longitude,
                        'format' => 'json',
                        'addressdetails' => 1,
                    ]);

                if ($response->ok()) {
                    $json = $response->json();
                    $alamatLogin = $json['display_name'] ?? "{$latitude}, {$longitude}";

                    Log::info('Reverse geocoding success', [
                        'lat' => $latitude,
                        'lon' => $longitude,
                        'alamat' => $alamatLogin,
                    ]);
                } else {
                    $alamatLogin = "{$latitude}, {$longitude}";
                    Log::warning('Reverse geocoding gagal', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                $alamatLogin = "{$latitude}, {$longitude}";
                Log::error('Reverse geocoding error', [
                    'error' => $e->getMessage(),
                    'lat' => $latitude,
                    'lon' => $longitude,
                ]);
            }
        }

        // 🔹 Ambil lokasi rumah dari profile user
        $profileLokasi = optional($user->profile)->lokasi_rumah;

        // 🔹 Kalau lokasi rumah masih kosong → isi dengan alamat login terakhir
        if (!$profileLokasi && $alamatLogin) {
            if (!$user->profile) {
                $user->profile()->create([
                    'lokasi_rumah' => $alamatLogin,
                ]);
            } else {
                $user->profile->update([
                    'lokasi_rumah' => $alamatLogin,
                ]);
            }
            $profileLokasi = $alamatLogin;
        }

        // 🔹 Kirim data ke view
        return view('lokasi.index', [
            'lokasiLogin' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'attempted_at' => optional($lokasiLogin)->attempted_at,
            ],
            'alamatLogin' => $alamatLogin,
            'profileLokasi' => $profileLokasi,
        ]);
    }


    public function uploadselfie(Request $request)
    {
        try {
            // 🔹 Ambil user yang sedang login
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi.'
                ], 401);
            }

            // 🔹 Ambil data gambar
            $image = $request->input('image');
            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada gambar dikirim.'
                ], 400);
            }

            // 🔹 Bersihkan prefix base64 (data:image/jpeg;base64, dst)
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            if ($imageData === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal decode data gambar.'
                ], 400);
            }

            // 🔹 Buat nama file unik → selalu ada .jpg
            $unique = uniqid();
            $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $user->name);
            $fileName = "selfie_{$user->id}_{$safeName}_{$unique}.jpg";

            // 🔹 Tentukan path
            $relativePath = "assets/images/selfie/{$fileName}";
            $targetDir = public_path('assets/images/selfie');
            $targetPath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

            // 🔹 Pastikan folder ada
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // 🔹 Simpan file
            file_put_contents($targetPath, $imageData);

            // 🔹 Update ke profil user (kolom `foto`)
            $userProfile = $user->profile; // relasi user -> user_profiles
            if (!$userProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil user tidak ditemukan.'
                ], 404);
            }

            $userProfile->foto = $relativePath;
            $userProfile->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto selfie berhasil disimpan.',
                'file'    => $relativePath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }




    public function uploadfaremuk(Request $request): JsonResponse
    {
        try {
            Log::info('📥 [uploadfaremuk] Mulai proses verifikasi wajah', [
                'user_id' => Auth::id(),
                'request_files' => $request->hasFile('images') ? count($request->file('images')) : 0,
            ]);

            // =======================================================
            // 1. Cek apakah user login
            // =======================================================
            $user = Auth::user();
            if (!$user) {
                Log::warning('❌ User tidak terautentikasi saat upload verifikasi wajah.');
                return response()->json([
                    'success' => false,
                    'status'  => 'unauthenticated',
                    'message' => 'User tidak terautentikasi. Silakan login kembali.'
                ], 401);
            }

            // =======================================================
            // 2. Validasi file upload
            // =======================================================
            try {
                $request->validate([
                    'images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('⚠️ Validasi gagal upload verifikasi wajah', [
                    'user_id' => $user->id,
                    'errors'  => $e->errors(),
                ]);
                return response()->json([
                    'success' => false,
                    'status'  => 'validation_failed',
                    'message' => 'Validasi gagal. Silakan cek file yang diupload.',
                    'errors'  => $e->errors()
                ], 422);
            }

            $images = $request->file('images');
            if (!$images || count($images) === 0) {
                Log::warning('⚠️ Tidak ada file gambar yang dikirim untuk verifikasi wajah.', [
                    'user_id' => $user->id,
                ]);
                return response()->json([
                    'success' => false,
                    'status'  => 'no_file',
                    'message' => 'Tidak ada file gambar yang diupload.'
                ], 400);
            }

            // =======================================================
            // 3. Simpan file ke folder sementara
            // =======================================================
            $tempFiles = [];
            foreach ($images as $index => $image) {
                if (!$image->isValid()) {
                    Log::warning("⚠️ File ke-{$index} tidak valid", ['user_id' => $user->id]);
                    continue;
                }

                $unique    = uniqid();
                $fileName  = "verifikasi_temp_{$user->id}_{$index}_{$unique}." .
                    $image->getClientOriginalExtension();

                $relativePath = 'assets/images/verifikasi/temp/' . $fileName;
                $targetDir    = public_path('assets/images/verifikasi/temp');

                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                    Log::info("📂 Membuat folder temp: {$targetDir}");
                }

                $image->move($targetDir, $fileName);
                Log::info("💾 File sementara tersimpan", [
                    'user_id' => $user->id,
                    'file'    => $relativePath,
                ]);

                $tempFiles[] = $relativePath;
            }

            // =======================================================
            // 4. Ambil profil user
            // =======================================================
            $userProfile = $user->profile;
            if (!$userProfile) {
                Log::error('❌ Profil user tidak ditemukan.', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'status'  => 'profile_not_found',
                    'message' => 'Profil user tidak ditemukan.'
                ], 404);
            }

            // =======================================================
            // 5. Panggil Face Recognition Service
            // =======================================================
            $selfieUtama = public_path($userProfile->foto);
            $absoluteTempFiles = array_map(fn($f) => public_path($f), $tempFiles);

            Log::info('🔍 Memanggil FaceRecognitionService', [
                'user_id'   => $user->id,
                'selfie'    => $selfieUtama,
                'uploads'   => $absoluteTempFiles,
            ]);

            $hasilVerifikasi = app(FaceRecognitionService::class)
                ->compareWithActions($selfieUtama, $absoluteTempFiles);

            Log::info('📊 Hasil verifikasi wajah', [
                'user_id' => $user->id,
                'hasil'   => $hasilVerifikasi,
            ]);

            // =======================================================
            // 6. Cek kecocokan wajah
            // =======================================================
            if (!empty($hasilVerifikasi['match']) && $hasilVerifikasi['match'] === true) {
                $savedFiles = [];
                foreach ($tempFiles as $tempPath) {
                    $fileName  = basename($tempPath);
                    $finalPath = 'assets/images/verifikasi/' . $fileName;
                    $finalDir  = public_path('assets/images/verifikasi');

                    if (!file_exists($finalDir)) {
                        mkdir($finalDir, 0777, true);
                        Log::info("📂 Membuat folder permanen: {$finalDir}");
                    }

                    rename(public_path($tempPath), $finalDir . '/' . $fileName);

                    $savedFiles[] = $finalPath;
                    Log::info("📦 File dipindahkan ke permanen", [
                        'user_id' => $user->id,
                        'file'    => $finalPath,
                    ]);
                }

                // 🔑 Update hanya kolom `verifikasi_muka`
                $userProfile->verifikasi_muka = json_encode($savedFiles);
                $userProfile->save();

                Log::info('✅ Verifikasi wajah berhasil & profil diperbarui', [
                    'user_id' => $user->id,
                    'files'   => $savedFiles,
                ]);

                return response()->json([
                    'success' => true,
                    'status'  => 'verified',
                    'message' => '✅ Wajah berhasil diverifikasi.',
                    'files'   => $savedFiles,
                    'score'   => $hasilVerifikasi['score'] ?? null,
                    'details' => $hasilVerifikasi['details'] ?? []
                ]);
            } else {
                foreach ($tempFiles as $tempPath) {
                    $abs = public_path($tempPath);
                    if (file_exists($abs)) {
                        unlink($abs);
                        Log::info("🗑️ Menghapus file gagal verifikasi", [
                            'user_id' => $user->id,
                            'file'    => $abs,
                        ]);
                    }
                }

                Log::warning('❌ Verifikasi wajah gagal', [
                    'user_id' => $user->id,
                    'hasil'   => $hasilVerifikasi,
                ]);

                return response()->json([
                    'success' => false,
                    'status'  => 'failed',
                    'message' => '❌ Wajah tidak cocok dengan selfie utama. Silakan coba lagi.',
                    'files'   => [],
                    'score'   => $hasilVerifikasi['score'] ?? null,
                    'details' => $hasilVerifikasi['details'] ?? []
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('🔥 Error umum saat uploadfaremuk', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'status'  => 'server_error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
