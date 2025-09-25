@include('partials.header')

<body class="hold-transition sidebar-mini layout-fixed" data-panel-auto-height-mode="height">

    <!-- Loader Overlay -->
    @include('partials.loader')


    <div class="wrapper">
        <!-- Navbar -->
        @include('partials.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('partials.sidebar')
        <!-- /Main Sidebar Container -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper iframe-mode" data-widget="iframe" data-loading-screen="750">
            <div class="nav navbar navbar-expand navbar-white2 navbar-light border-bottom p-0">
                <div class="nav-item dropdown">
                    <a class="nav-link bg-danger dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                        aria-haspopup="true" aria-expanded="false">Close</a>
                    <div class="dropdown-menu mt-0">
                        <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all">Close
                            All</a>
                        <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all-other">Close
                            All Other</a>
                    </div>
                </div>
                <a class="nav-link bg-light" href="#" data-widget="iframe-scrollleft"><i
                        class="fas fa-angle-double-left"></i></a>
                <ul class="navbar-nav overflow-hidden" role="tablist"></ul>
                <a class="nav-link bg-light" href="#" data-widget="iframe-scrollright"><i
                        class="fas fa-angle-double-right"></i></a>
                <a class="nav-link bg-light" href="#" data-widget="iframe-fullscreen"><i
                        class="fas fa-expand"></i></a>
            </div>
            <div class="tab-content">

                @php
                    use Illuminate\Support\Facades\Auth;
                    $user = Auth::user();
                @endphp

                <div class="tab-empty text text-center">
                    <div class="tab-empty-content animation__shake" style="font-size: 30px;">

                        <img src="{{ asset('assets/images/icon/logopt.png') }}" alt="Pinetmart Logo" height="85"
                            width="85" />
                        <br>
                        <h5> Hallo...<b>{{ $user->name }} </b></h5>
                        <h5> Selamat Datang di <b style="color: rgb(255, 213, 0)">IndoPinetMart</b></h5>
                        <h5> Anda Login Sebagai : <b>{{ $user->role->name }}</b></h5>


                    </div>
                </div>
                <div class="tab-loading" id="loadingScreen">
                    <div>
                        <h2 class="text text-center display-4">
                            <img class="animation__shake" src="{{ asset('assets/images/icon/logopt.png') }}"
                                alt="logo" height="50" width="50">
                            <span class="animation__shake" style="font-size: 30px;">Please Wait...</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->

        <div class="modal fade" id="persetujuanLayananModal" tabindex="-1" role="dialog"
            aria-labelledby="persetujuanTitle" aria-modal="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h6 class="modal-title" style="color: gold; font-weight: bold;">Persetujuan Layanan
                            IndoPinetMart</h6>

                    </div>

                    <div class="modal-body">
                        <div class="mb-3"
                            style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
                            <h6><strong>Perjanjian Penggunaan Aplikasi</strong></h6>
                            <p>
                                Dengan menggunakan aplikasi <strong>Management Perusahaan Digital
                                    IndoPinetMart</strong>,<br>
                                saya menyatakan telah membaca, memahami, dan menyetujui ketentuan berikut:
                            </p>
                            <ol>
                                <li>
                                    <strong>Penggunaan Aplikasi:</strong><br>
                                    Aplikasi ini diperuntukkan hanya bagi karyawan, mitra, prartner dan manajemen
                                    IndoPinetMart. <br>
                                    Pengguna wajib menjaga kerahasiaan akun dan kata sandi. Semua aktivitas yang
                                    dilakukan melalui akun merupakan tanggung jawab pemilik akun.
                                </li>
                                <li>
                                    <strong>Kerahasiaan Data:</strong><br>
                                    Seluruh data pribadi, data perusahaan, serta informasi rahasia yang diakses melalui
                                    aplikasi dilindungi <br>
                                    dan tidak boleh disalahgunakan. Pengguna dilarang menyebarkan data perusahaan kepada
                                    pihak ketiga tanpa izin tertulis dari manajemen.
                                </li>
                                <li>
                                    <strong>Hak & Kewajiban Pengguna:</strong><br>
                                    Pengguna berhak menggunakan aplikasi sesuai fungsinya. <br>
                                    Pengguna wajib mematuhi peraturan perusahaan dan ketentuan yang berlaku di dalam
                                    aplikasi.
                                </li>
                                <li>
                                    <strong>Pembatasan Tanggung Jawab:</strong><br>
                                    Perusahaan tidak bertanggung jawab atas kerugian akibat penyalahgunaan akun oleh
                                    pihak lain.<br>
                                    Perusahaan berhak melakukan pembekuan akun jika ditemukan pelanggaran.
                                </li>
                                <li>
                                    <strong>Perubahan Ketentuan:</strong><br>
                                    Perusahaan berhak memperbarui ketentuan ini sewaktu-waktu dengan pemberitahuan
                                    melalui aplikasi.
                                </li>
                            </ol>
                            <p>
                                Pelanggaran ketentuan ini dapat mengakibatkan sanksi administratif, pemutusan hubungan
                                kerja, <br>
                                dan/atau proses hukum sesuai peraturan yang berlaku.
                            </p>
                        </div>

                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="setujuCheckbox">
                            <label class="form-check-label" for="setujuCheckbox">
                                Saya telah membaca dan menyetujui persyaratan penggunaan aplikasi
                                <strong>IndoPinetMart</strong>.
                            </label>
                        </div>

                        <span class="mt-2"><b>*Klik Box Untuk Memberi Tanda cheklist Sebagai Tanda
                                Menyetujui</b></span><br>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-success" id="btnSetuju" disabled>Setuju &
                            Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 🔹 Modal Lengkapi Profil – Versi Lengkap & Diperbaiki -->
        <!-- ========================================================= -->

        <div class="modal fade" id="lengkapiProfileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- ================= Header ================= -->
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h6 class="modal-title" style="color: gold; font-weight: bold;">
                            Lengkapi Data Profil
                        </h6>
                    </div>

                    <!-- ================= Body ================= -->
                    <div class="modal-body">
                        <form id="formLengkapiProfile">

                            <!-- 🔹 Hidden inputs (harus di dalam form) -->
                            <input type="hidden" id="province_hidden" name="province_id">
                            <input type="hidden" id="city_hidden" name="city_id">
                            <input type="hidden" id="district_hidden" name="district_id">
                            <input type="hidden" id="village_hidden" name="village_id">

                            <input type="hidden" id="province_name" name="province_name">
                            <input type="hidden" id="city_name" name="city_name">
                            <input type="hidden" id="district_name" name="district_name">
                            <input type="hidden" id="village_name" name="village_name">


                            <div class="row">
                                <!-- NIK Karyawan (Disabled) -->
                                <div class="col-md-6">
                                    <label for="nik_karyawan">NIK Karyawan *</label>
                                    <input type="text" name="nik_karyawan" id="nik_karyawan" class="form-control"
                                        value="{{ old('nik_karyawan', $profile->nik_karyawan ?? '') }}" disabled>
                                </div>

                                <!-- NIK KTP -->
                                <div class="col-md-6">
                                    <label for="nik_ktp">NIK KTP *</label>
                                    <input type="number" name="nik_ktp" id="nik_ktp" class="form-control"
                                        placeholder="Masukkan NIK KTP"
                                        value="{{ old('nik_ktp', $profile->nik_ktp ?? '') }}">
                                </div>

                                <!-- Nama Lengkap -->
                                <div class="col-md-6 mt-3">
                                    <label for="nama_lengkap">Nama Lengkap *</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                                        placeholder="Masukkan Nama Lengkap"
                                        value="{{ old('nama_lengkap', $profile->nama_lengkap ?? '') }}">
                                </div>

                                <!-- No Telepon -->
                                <div class="col-md-6 mt-3">
                                    <label for="no_tlp">No. Telepon *</label>
                                    <input type="number" name="no_tlp" id="no_tlp" class="form-control"
                                        placeholder="Masukkan No. Telepon"
                                        value="{{ old('no_tlp', $profile->no_tlp ?? '') }}">
                                </div>

                                <!-- Provinsi -->
                                <div class="col-md-6 mt-3">
                                    <label for="province_id">Provinsi *</label>
                                    <select id="province_id" class="form-control" required>
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                </div>

                                <!-- Kota/Kabupaten -->
                                <div class="col-md-6 mt-3">
                                    <label for="city_id">Kota/Kabupaten *</label>
                                    <select id="city_id" class="form-control" required>
                                        <option value="">-- Pilih Kota/Kabupaten --</option>
                                    </select>
                                </div>

                                <!-- Kecamatan -->
                                <div class="col-md-4 mt-3">
                                    <label for="district_id">Kecamatan *</label>
                                    <select id="district_id" class="form-control" required>
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                </div>

                                <!-- Kelurahan/Desa -->
                                <div class="col-md-4 mt-3">
                                    <label for="village_id">Kelurahan/Desa *</label>
                                    <select id="village_id" class="form-control" required>
                                        <option value="">-- Pilih Kelurahan/Desa --</option>
                                    </select>
                                </div>

                                <!-- Kode Pos -->
                                <div class="col-md-4 mt-3">
                                    <label for="kodepos">Kode Pos *</label>
                                    <input type="number" name="kodepos" id="kodepos" class="form-control"
                                        value="{{ old('kodepos', $profile->kodepos ?? '') }}" required>
                                </div>

                                <!-- Alamat Rumah -->
                                <div class="col-md-12 mt-3">
                                    <label for="alamat_rumah">Alamat Rumah *</label>
                                    <textarea name="alamat_rumah" id="alamat_rumah" class="form-control" rows="3"
                                        placeholder="Masukkan Alamat Rumah">{{ old('alamat_rumah', $profile->alamat_rumah ?? '') }}</textarea>
                                </div>

                                <!-- Bank -->
                                <div class="col-md-4 mt-3">
                                    <label for="bank">Bank *</label>
                                    <input type="text" name="bank" id="bank" class="form-control"
                                        value="{{ old('bank', $profile->bank ?? '') }}">
                                </div>

                                <!-- No Rekening -->
                                <div class="col-md-4 mt-3">
                                    <label for="norek">No Rek *</label>
                                    <input type="number" name="norek" id="norek" class="form-control"
                                        value="{{ old('norek', $profile->norek ?? '') }}">
                                </div>

                                <!-- Kontak Darurat -->
                                <div class="col-md-4 mt-3">
                                    <label for="kontakdarurat">Kontak Darurat *</label>
                                    <input type="number" name="kontakdarurat" id="kontakdarurat"
                                        class="form-control"
                                        value="{{ old('kontak_darurat', $profile->kontak_darurat ?? '') }}">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ================= Footer ================= -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-success" id="btnSimpanProfile" disabled>
                            Simpan & Lanjutkan
                        </button>
                    </div>

                </div>
            </div>
        </div>




        <!-- Modal Verifikasi -->
        <div class="modal fade" id="verificationModal" data-bs-backdrop="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h6 class="modal-title" style="color: gold; font-weight: bold;">
                            Lengkapi Data Profil
                        </h6>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Beberapa data Anda masih belum lengkap. Silakan lakukan verifikasi berikut:
                        </p>

                        <ul class="list-group mb-3" id="missingFieldsList">
                            <!-- Akan diisi otomatis via JS -->
                        </ul>

                        <p class="text-muted small">
                            Pastikan semua data terisi untuk melanjutkan proses verifikasi akun.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button id="btnLengkapi" class="btn btn-primary">Lengkapi Sekarang</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal Foto KTP -->
        <div class="modal fade" id="ktpModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-modal">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h6 class="modal-title" style="color: gold; font-weight: bold;">Ambil Foto KTP</h6>
                    </div>
                    <div class="modal-body"
                        style="height: 70vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <p>Silakan posisikan KTP di dalam kotak:</p>

                        <!-- Container video + overlay -->
                        <div class="ktp-frame position-relative">
                            <video id="videoKTP" autoplay playsinline
                                style="width:100%; border:1px solid #ddd; border-radius:5px;"></video>
                            <canvas id="canvasKTP" style="display:none; position:absolute; top:0; left:0;"></canvas>
                            <div id="ktpFrameOverlay"
                                style="position:absolute; top:0; left:0; width:100%; height:100%; border: 2px dashed #6a0dad; pointer-events:none;">
                            </div>
                        </div>

                        <!-- Tombol kontrol -->
                        <div class="mt-2">
                            <button id="takePhoto" class="btn btn-success">Ambil Foto</button>
                            <button id="resetPhoto" class="btn btn-danger" style="display:none;">Reset</button>
                            <button id="savePhoto" class="btn btn-primary" style="display:none;">Save</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnNextSelfie" class="btn btn-primary">Selanjutnya (Selfie)</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Lokasi Rumah -->
        <div class="modal fade" id="lokasiRumahModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-modal">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h6 class="modal-title" style="color: gold; font-weight: bold;">
                            Ambil Lokasi Rumah
                        </h6>
                    </div>
                    <div class="modal-body" style="height: 70vh;">
                        <p>Silakan ambil titik lokasi rumah Anda:</p>
                        <div id="mapLokasi" style="width: 100%; height: 100%; border: 2px solid #6a0dad;"></div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnSaveLokasi" class="btn btn-success">Simpan Lokasi</button>
                        <button id="btnNextSelfie" class="btn btn-primary">Selanjutnya (Selfie)</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Foto Selfie -->
        <div class="modal fade" id="selfieModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-modal">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h6 class="modal-title" style="color: gold; font-weight: bold;">
                            Ambil Foto Selfie
                        </h6>
                    </div>
                    <div class="modal-body"
                        style="height: 70vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <p>Silakan posisikan wajah Anda di dalam kotak:</p>

                        <!-- Container video + overlay -->
                        <div class="selfie-frame" style="position: relative; width: 100%; max-width: 400px;">
                            <video id="videoSelfie" autoplay playsinline
                                style="width: 100%; border-radius: 10px;"></video>
                            <div id="selfieFrameOverlay"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                                border: 4px solid rgba(255,215,0,0.8); border-radius: 15px; box-sizing: border-box;">
                            </div>
                            <canvas id="canvasSelfie"
                                style="display:none; width: 100%; border-radius: 10px;"></canvas>
                        </div>

                        <!-- Tombol kontrol -->
                        <div class="mt-3 text-center">
                            <button id="takeSelfie" class="btn btn-success">Ambil Foto</button>
                            <button id="resetSelfie" class="btn btn-danger" style="display:none;">Reset</button>
                            <button id="saveSelfie" class="btn btn-primary" style="display:none;">Save</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnFinish" class="btn btn-primary">Selesai</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal Verifikasi Wajah -->
        <!-- ============================= -->
        <!-- Modal Verifikasi Wajah        -->
        <!-- ============================= -->
        <!-- Modal Verifikasi Wajah -->
        <div class="modal fade" id="verifikasiMukaModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-modal">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h6 class="modal-title" style="color: gold; font-weight: bold;">
                            Verifikasi Wajah
                        </h6>
                    </div>

                    <div class="modal-body"
                        style="height: 70vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <p>Ikuti instruksi pada kotak di layar:</p>

                        <!-- GANTI ID MENJADI videoContainer -->
                        <div id="videoContainer" class="verifikasi-frame"
                            style="position: relative; width: 100%; max-width: 400px; aspect-ratio: 4/5;">

                            <!-- Video Kamera -->
                            <video id="videoVerifikasi" autoplay playsinline muted
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; background: #606060;"></video>

                            <!-- Overlay Instruksi -->
                            <div id="verifikasiFrameOverlay"
                                style="position: absolute; top: 10%; left: 10%; width: 80%; height: 80%;
                            border: 3px dashed gold; border-radius: 8px; pointer-events: none;
                            display: flex; align-items: center; justify-content: center;">
                                <span id="actionText"
                                    style="color: gold; font-weight: bold; font-size: 1.3em;"></span>
                            </div>

                            <!-- Canvas untuk Capture -->
                            <canvas id="canvasVerifikasi"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                        display: none; border-radius: 8px;"></canvas>
                        </div>

                        <!-- Tombol kontrol -->
                        <div class="mt-2">
                            <button id="startVerifikasi" class="btn btn-success">Mulai Verifikasi</button>
                            <button id="resetVerifikasi" class="btn btn-danger" style="display:none;">Reset</button>
                            <button id="saveVerifikasi" class="btn btn-primary"
                                style="display:none;">Selesai</button>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btnFinish" class="btn btn-success">Selesai</button>
                    </div>
                </div>
            </div>
        </div>






        @include('partials.footer')

    </div>
    <!-- ./wrapper -->

    @include('partials.script')


</body>


</html>
