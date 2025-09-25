@include('partials.header')

<div class="content-wrapper">

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi Profil!',
                text: '{{ session('warning') }}'
            });
        </script>
    @endif

    <br>
    @include('partials.loader')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-purple card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                {{-- Foto profil --}}
                                <img src="{{ $userData['photo'] }}" alt="Profile Photo" class="rounded-circle mb-2"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            {{-- {{ dd($userData['photo']) }} --}}
                            {{-- Bagian Nama + Role + Tombol Edit Foto --}}
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <h3 class="profile-username mb-0"><b>{{ $userData['name'] }}</b></h3>
                                    <p class="text-muted mb-0"><b>{{ $userData['role_name'] }}</b></p>
                                </div>
                                <div>
                                    {{-- Tombol Edit Foto --}}
                                    <button type="button" class="btn btn-xs btn-warning" data-toggle="modal"
                                        data-target="#editPhotoModal">
                                        Edit Foto
                                    </button>


                                </div>
                            </div>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ $userData['email'] }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Password</b> <a class="float-right">***********************</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Status</b> <a class="float-right"><span
                                            class="badge bg-{{ $userData['status_badge'] }}">
                                            {{ $userData['status_text'] }}
                                        </span></a>
                                </li>

                                {{-- Tampilkan ID Referal kalau role_name = referal --}}
                                @if (strtolower($userData['role_name']) === 'referal')
                                    <li class="list-group-item">
                                        <b>Id_Referal</b>
                                        <a class="float-right">
                                            {{ $userData['id_referal'] ?? '-' }}
                                        </a>
                                    </li>
                                @endif

                                {{-- Tampilkan ID Mitra kalau role_name = mitra --}}
                                @if (strtolower($userData['role_name']) === 'mitra')
                                    <li class="list-group-item">
                                        <b>Id_Mitra</b>
                                        <a class="float-right">
                                            {{ $userData['id_mitra'] ?? '-' }}
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-purple">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-home mr-1"></i> Alamat</strong>

                            <p class="text-muted">
                                {{ $userData['alamat_rumah'] ?? '-' }}<br>
                                {{ $userData['village_name'] ?? '-' }}, {{ $userData['district_name'] ?? '-' }}<br>
                                {{ $userData['city_name'] ?? '-' }}, {{ $userData['province_name'] ?? '-' }}
                                {{ $userData['kodepos'] ?? '' }}
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                            <div id="user-map" style="height: 300px; width: 100%; margin-top: 10px;"></div>

                            <hr>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header" style="background-color: #6a0dad;">
                            <h3 class="card-title" style="color: gold; font-weight: bold;">
                                My Profile
                            </h3>
                        </div>
                        <div class="card-body">


                            @php
                                // Mapping status ke class bootstrap/custom
                                $classMap = [
                                    'valid' => 'is-valid',
                                    'warning' => 'is-warning',
                                    'error' => 'is-invalid',
                                ];
                            @endphp

                            <div class="row">
                                <!-- Nomor Induk (NIK Karyawan) -->
                                @php $nikKaryawanClass = $classMap[$status['nik_karyawan'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Nomor Induk</label>
                                    <input type="text" name="nik_karyawan"
                                        class="form-control {{ $nikKaryawanClass }}"
                                        value="{{ old('nik_karyawan', $userData['nik_karyawan'] ?? '') }}" disabled>
                                </div>

                                <!-- Nama Lengkap -->
                                @php $namaLengkapClass = $classMap[$status['nama_lengkap'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap"
                                        class="form-control {{ $namaLengkapClass }}"
                                        value="{{ old('nama_lengkap', $userData['nama_lengkap'] ?? '') }}" disabled>
                                </div>

                                <!-- NIK KTP -->
                                @php $nikKtpClass = $classMap[$status['nik_ktp'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*NIK KTP</label>
                                    <input type="text" name="nik_ktp" class="form-control {{ $nikKtpClass }}"
                                        value="{{ old('nik_ktp', $userData['nik_ktp'] ?? '') }}" disabled>
                                </div>

                                <!-- No. Telepon -->
                                @php $noTlpClass = $classMap[$status['no_tlp'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*No. Telepon</label>
                                    <input type="text" name="no_tlp" class="form-control {{ $noTlpClass }}"
                                        value="{{ old('no_tlp', $userData['no_tlp'] ?? '') }}" disabled>
                                </div>

                                <!-- Alamat Rumah -->
                                @php $alamatClass = $classMap[$status['alamat_rumah'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Alamat Rumah</label>
                                    <input type="text" name="alamat_rumah" class="form-control {{ $alamatClass }}"
                                        value="{{ old('alamat_rumah', $userData['alamat_rumah'] ?? '') }}" disabled>
                                </div>

                                <!-- Kota -->
                                @php $cityClass = $classMap[$status['city_name'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Kota</label>
                                    <input type="text" name="city_name" class="form-control {{ $cityClass }}"
                                        value="{{ old('city_name', $userData['city_name'] ?? '') }}" disabled>
                                </div>

                                <!-- Kecamatan -->
                                @php $districtClass = $classMap[$status['district_name'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Kecamatan</label>
                                    <input type="text" name="district_name"
                                        class="form-control {{ $districtClass }}"
                                        value="{{ old('district_name', $userData['district_name'] ?? '') }}" disabled>
                                </div>

                                <!-- Kelurahan / Desa -->
                                @php $villageClass = $classMap[$status['village_name'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Kelurahan / Desa</label>
                                    <input type="text" name="village_name" class="form-control {{ $villageClass }}"
                                        value="{{ old('village_name', $userData['village_name'] ?? '') }}" disabled>
                                </div>

                                <!-- Kode Pos -->
                                @php $kodeposClass = $classMap[$status['kodepos'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Kode Pos</label>
                                    <input type="text" name="kodepos" class="form-control {{ $kodeposClass }}"
                                        value="{{ old('kodepos', $userData['kodepos'] ?? '') }}" disabled>
                                </div>

                                <!-- Provinsi -->
                                @php $provinceClass = $classMap[$status['province_name'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Provinsi</label>
                                    <input type="text" name="province_name"
                                        class="form-control {{ $provinceClass }}"
                                        value="{{ old('province_name', $userData['province_name'] ?? '') }}" disabled>
                                </div>

                                <!-- Nomor Rekening -->
                                @php $norekClass = $classMap[$status['norek'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*No. Rekening</label>
                                    <input type="text" name="norek" class="form-control {{ $norekClass }}"
                                        value="{{ old('norek', $userData['norek'] ?? '') }}" disabled>
                                </div>

                                <!-- Bank -->
                                @php $bankClass = $classMap[$status['bank'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Bank</label>
                                    <input type="text" name="bank" class="form-control {{ $bankClass }}"
                                        value="{{ old('bank', $userData['bank'] ?? '') }}" disabled>
                                </div>

                                <!-- Kontak Darurat -->
                                @php $kontakClass = $classMap[$status['kontak_darurat'] ?? ''] ?? ''; @endphp
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Kontak Darurat</label>
                                    <input type="text" name="kontak_darurat"
                                        class="form-control {{ $kontakClass }}"
                                        value="{{ old('kontak_darurat', $userData['kontak_darurat'] ?? '') }}"
                                        disabled>
                                </div>

                                <!-- Tanggal Dibuat (created_at) -->
                                <div class="col-sm-6 mb-3">
                                    <label class="col-form-label">*Tanggal Dibuat</label>
                                    <input type="text" name="created_at" class="form-control"
                                         value="{{ old('created_at', $userData['created_at'] ?? '') }}" disabled>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->



        <!-- Modal Edit Foto -->
        <div class="modal fade" id="editPhotoModal" tabindex="-1" role="dialog"
            aria-labelledby="editPhotoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6a0dad;">
                        <h5 class="modal-title" id="editPhotoModalLabel" style="color: gold; font-weight: bold;">
                            Ubah Foto Profil
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formEditPhoto" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                {{-- <label for="photo" class="form-label">Pilih Foto Baru</label>
                                <input type="file" name="photo" id="photo" class="form-control"
                                    accept="image/*" required> --}}

                                <div class="form-group">
                                    <label for="exampleInputFile">Pilih Foto Baru</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="photo" id="photo"
                                                class="custom-file-input" accept="image/*" required>
                                            <label class="custom-file-label">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="text-center">
                                <img id="previewPhoto" src="#" alt="Preview Foto"
                                    class="rounded-circle d-none"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-xs btn-secondary"
                                data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-xs btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


@include('partials.script')

@include('admin.profile.script_profile')
