    <!-- jQuery -->
    <script src="{{ asset('tamplate/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('tamplate/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('assets/js/logout.js') }}"></script>
    <script src="{{ asset('tamplate/dist/js/adminlte.min.js') }}"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- ✅ SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <script src="{{ asset('tamplate/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        // Ambil APP_URL dari .env Laravel
        window.APP_URL = "{{ env('APP_URL') }}";
    </script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('tamplate/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('tamplate/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Face Recognation -->
    <script src="{{ asset('assets/js/face-api.min.js') }}"></script>



    <script>
        //USER TABLE
        $(function() {
            $("#user_table").DataTable({
                responsive: true,
                lengthChange: true, // ✅ Aktifkan dropdown jumlah data
                autoWidth: false,
                pageLength: 10, // ✅ Default tampil 10 data
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ] // ✅ Pilihan jumlah data
            });
        });

        //TAMBAH_MENU_TABLE
        $(function() {
            $("#tambah_menu").DataTable({
                responsive: true,
                lengthChange: true, // ✅ Aktifkan dropdown jumlah data
                autoWidth: false,
                pageLength: 10, // ✅ Default tampil 10 data
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ] // ✅ Pilihan jumlah data
            });
        });


        //ROLE_MENU_TABLE
        $(function() {
            $("#role_menu").DataTable({
                responsive: true,
                lengthChange: true, // ✅ Aktifkan dropdown jumlah data
                autoWidth: false,
                pageLength: 10, // ✅ Default tampil 10 data
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ] // ✅ Pilihan jumlah data
            });
        });

        //PERMISSIONS TABLE
        $(function() {
            $("#table_permissions").DataTable({
                responsive: true,
                lengthChange: true, // ✅ Aktifkan dropdown jumlah data
                autoWidth: false,
                pageLength: 10, // ✅ Default tampil 10 data
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ] // ✅ Pilihan jumlah data
            });
        });

        //ROLE PERMISSIONS TABLE
        $(function() {
            $("#role_permissions").DataTable({
                responsive: true,
                lengthChange: true, // ✅ Aktifkan dropdown jumlah data
                autoWidth: false,
                pageLength: 10, // ✅ Default tampil 10 data
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ] // ✅ Pilihan jumlah data
            });
        });
    </script>


    <script>
        function checkResetSessions(userId, token) {
            axios.get(`/reset-sessions?user=${userId}&token=${token}`)
                .then(response => {
                    if (response.data.message) {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: response.data.message,
                            icon: 'warning',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            // Logout user via endpoint Laravel
                            axios.post('/auth/logout').finally(() => {
                                window.location.href = '/login';
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                });
        }
    </script>


    <script>
        $(document).ready(function() {
            const btnSimpan = $('#btnSimpanProfile');
            const btnSetuju = $('#btnSetuju');
            const checkboxSetuju = $('#setujuCheckbox');
            let verificationInterval = null;

            // ----------------- Loader -----------------


            // ----------------- Validasi Input Profil -----------------
            function validateProfileInputs() {
                let valid = true;
                $('#formLengkapiProfile input').each(function() {
                    if ($(this).val().trim() === '') valid = false;
                });
                btnSimpan.prop('disabled', !valid);
            }
            $('#formLengkapiProfile input').on('input', validateProfileInputs);

            // ----------------- Tombol Setuju Persetujuan -----------------
            checkboxSetuju.on('change', function() {
                btnSetuju.prop('disabled', !checkboxSetuju.is(':checked'));
            });

            btnSetuju.on('click', function() {
                showLoader("Menyimpan persetujuan layanan...");
                axios.post("{{ route('app_ipm.accept_terms') }}")
                    .then(resp => {
                        hideLoader();
                        if (resp.data.type === 'success') {
                            $('#persetujuanLayananModal').modal('hide');

                            // Buka modal lengkapi profil setelah persetujuan
                            showLoader("Memeriksa profil...");
                            setTimeout(() => {
                                $('#lengkapiProfileModal').modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('#lengkapiProfileModal').on('shown.bs.modal', hideLoader);
                            }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: resp.data.message || 'Terjadi kesalahan'
                            });
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan. Silakan coba lagi.'
                        });
                    });
            });

            // ----------------- Cek Verification -----------------
            function startVerificationCheck() {
                if (verificationInterval) return;

                verificationInterval = setInterval(() => {
                    axios.get("{{ route('app_ipm.check_verification') }}")
                        .then(response => {
                            const modalEl = document.getElementById('verificationModal');

                            // ✅ Kalau sudah lengkap → jangan tampilkan modal
                            if (!response.data.need_verification) {
                                if (modalEl) {
                                    $('#verificationModal').modal('hide');
                                }
                                clearInterval(verificationInterval);
                                verificationInterval = null;
                                hideLoader();
                                return;
                            }

                            // ❌ Kalau masih ada yang belum lengkap → tampilkan modal
                            if (response.data.need_verification) {
                                if (modalEl) { // <-- cek modal ada dulu
                                    let verModal = new bootstrap.Modal(modalEl);
                                    verModal.show();

                                    let list = response.data.missing_fields.map(f =>
                                        `<li class="list-group-item text-danger">${f} belum lengkap</li>`
                                    ).join('');

                                    $('#missingFieldsList').html(list);
                                    hideLoader();
                                } else {
                                    console.warn(
                                        "⚠️ Modal verificationModal tidak ditemukan di halaman!");
                                }
                            }
                        })
                        .catch(err => console.error("Error cek verifikasi:", err));
                }, 1000);
            }



            // ============================================================
            // 📌 SCRIPT PROFIL – Dropdown Wilayah, Hidden Inputs & Validasi
            // Versi Final Lengkap & Fix Load Provinsi
            // ============================================================

            // ============================================================
            // 🌍 Fungsi Load Provinces
            // ============================================================
            function loadProvinces(selectedProvinceName = null) {
                $('#province_id').html('<option>Loading...</option>');
                $('#city_id').html('<option value="">-- Pilih Kota/Kabupaten --</option>');
                $('#district_id').html('<option value="">-- Pilih Kecamatan --</option>');
                $('#village_id').html('<option value="">-- Pilih Kelurahan --</option>');
                $('#province_name, #province_hidden, #city_name, #district_name, #village_name, #zip_code').val('');
                $('#city_hidden, #district_hidden, #village_hidden').val('');

                axios.get('/api/provinces')
                    .then(res => {
                        const provinces = Array.isArray(res.data.data) ? res.data.data : [];
                        let options = '<option value="">-- Pilih Provinsi --</option>';
                        provinces.forEach(p => {
                            options += `<option value="${p.name}" data-id="${p.id}"
                    ${selectedProvinceName == p.name ? 'selected' : ''}>${p.name}</option>`;
                        });
                        $('#province_id').html(options);

                        // set hidden otomatis
                        const selected = $('#province_id').find(':selected');
                        $('#province_name').val(selected.val() || '');
                        $('#province_hidden').val(selected.data('id') || '');

                        if (selectedProvinceName) {
                            const selectedId = selected.data('id');
                            if (selectedId) {
                                loadCities(selectedId,
                                    "{{ old('city_name', $profile->city_name ?? '') }}");
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        $('#province_id').html('<option value="">-- Error load provinsi --</option>');
                    });
            }

            // ============================================================
            // 🏙️ Fungsi Load Cities
            // ============================================================
            function loadCities(provinceId, selectedCityName = null) {
                $('#city_id').html('<option>Loading...</option>');
                $('#district_id').html('<option value="">-- Pilih Kecamatan --</option>');
                $('#village_id').html('<option value="">-- Pilih Kelurahan --</option>');
                $('#city_name, #district_name, #village_name, #zip_code').val('');
                $('#city_hidden, #district_hidden, #village_hidden').val('');

                if (!provinceId)
                    return $('#city_id').html('<option value="">-- Pilih Kota/Kabupaten --</option>');

                axios.get('/api/cities/' + provinceId)
                    .then(res => {
                        const cities = Array.isArray(res.data.data) ? res.data.data : [];
                        let options = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                        cities.forEach(city => {
                            options += `<option value="${city.name}" data-id="${city.id}"
                    ${selectedCityName == city.name ? 'selected' : ''}>${city.name}</option>`;
                        });
                        $('#city_id').html(options);

                        const selected = $('#city_id').find(':selected');
                        $('#city_name').val(selected.val() || '');
                        $('#city_hidden').val(selected.data('id') || '');

                        if (selectedCityName) {
                            const selectedId = selected.data('id');
                            loadDistricts(selectedId,
                                "{{ old('district_name', $profile->district_name ?? '') }}");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        $('#city_id').html('<option value="">-- Error load kota --</option>');
                    });
            }

            // ============================================================
            // 🏘️ Fungsi Load Districts
            // ============================================================
            function loadDistricts(cityId, selectedDistrictName = null) {
                $('#district_id').html('<option>Loading...</option>');
                $('#village_id').html('<option value="">-- Pilih Kelurahan --</option>');
                $('#district_name, #village_name, #zip_code').val('');
                $('#district_hidden, #village_hidden').val('');

                if (!cityId)
                    return $('#district_id').html('<option value="">-- Pilih Kecamatan --</option>');

                axios.get('/api/districts/' + cityId)
                    .then(res => {
                        const districts = Array.isArray(res.data.data) ? res.data.data : [];
                        let options = '<option value="">-- Pilih Kecamatan --</option>';
                        districts.forEach(d => {
                            options += `<option value="${d.name}" data-id="${d.id}"
                    ${selectedDistrictName == d.name ? 'selected' : ''}>${d.name}</option>`;
                        });
                        $('#district_id').html(options);

                        const selected = $('#district_id').find(':selected');
                        $('#district_name').val(selected.val() || '');
                        $('#district_hidden').val(selected.data('id') || '');

                        if (selectedDistrictName) {
                            const selectedId = selected.data('id');
                            loadVillages(selectedId,
                                "{{ old('village_name', $profile->village_name ?? '') }}");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        $('#district_id').html('<option value="">-- Error load kecamatan --</option>');
                    });
            }

            // ============================================================
            // 🏡 Fungsi Load Villages (Kelurahan) – tanpa auto zip code
            // ============================================================
            function loadVillages(districtId, selectedVillageName = null) {
                $('#village_id').html('<option>Loading...</option>');
                $('#village_name').val('');
                $('#village_hidden').val('');

                if (!districtId) {
                    return $('#village_id').html('<option value="">-- Pilih Kelurahan --</option>');
                }

                axios.get('/api/villages/' + districtId)
                    .then(res => {
                        const villages = Array.isArray(res.data.data) ? res.data.data : [];
                        let options = '<option value="">-- Pilih Kelurahan --</option>';

                        villages.forEach(v => {
                            options += `
                    <option value="${v.name}" data-id="${v.id}"
                        ${selectedVillageName === v.name ? 'selected' : ''}>
                        ${v.name}
                    </option>`;
                        });

                        $('#village_id').html(options);

                        // Set value awal kalau ada yang selected
                        const selected = $('#village_id').find(':selected');
                        $('#village_name').val(selected.val() || '');
                        $('#village_hidden').val(selected.data('id') || '');
                    })
                    .catch(err => {
                        console.error(err);
                        $('#village_id').html('<option value="">-- Error load kelurahan --</option>');
                    });
            }
            // ============================================================
            // 🎯 Event Dropdown Change
            // ============================================================
            // Provinsi
            $('#province_id').on('change', function() {
                const provinceName = $(this).val() || '';
                const provinceId = $(this).find(':selected').data('id') || '';

                $('#province_name').val(provinceName);
                $('#province_hidden').val(provinceId);

                if (provinceId) loadCities(provinceId);

                $(this).toggleClass('is-valid', !!provinceId).toggleClass('is-invalid', !provinceId);
                $('#city_name, #district_name, #village_name, #zip_code').val('');
                $('#city_hidden, #district_hidden, #village_hidden').val('');

                // 🔥 pastikan validasi ulang
                validateProfileForm();
            });

            // Kota
            $('#city_id').on('change', function() {
                const cityName = $(this).val() || '';
                const cityId = $(this).find(':selected').data('id') || '';

                $('#city_name').val(cityName);
                $('#city_hidden').val(cityId);

                if (cityId) loadDistricts(cityId);

                $(this).toggleClass('is-valid', !!cityId).toggleClass('is-invalid', !cityId);
                $('#district_name, #village_name, #zip_code').val('');
                $('#district_hidden, #village_hidden').val('');

                // 🔥 pastikan validasi ulang
                validateProfileForm();

            });

            // Kecamatan
            $('#district_id').on('change', function() {
                const districtName = $(this).val() || '';
                const districtId = $(this).find(':selected').data('id') || '';

                $('#district_name').val(districtName);
                $('#district_hidden').val(districtId);

                if (districtId) loadVillages(districtId);

                $(this).toggleClass('is-valid', !!districtId).toggleClass('is-invalid', !districtId);
                $('#village_name').val('');
                $('#village_hidden').val('');

                // 🔥 pastikan validasi ulang
                validateProfileForm();
            });

            // Kelurahan
            $('#village_id').on('change', function() {
                const villageName = $(this).val() || '';
                const villageId = $(this).find(':selected').data('id') || '';

                $('#village_name').val(villageName);
                $('#village_hidden').val(villageId);



                $(this).toggleClass('is-valid', !!villageId).toggleClass('is-invalid', !villageId);

                // 🔥 langsung trigger validasi ulang supaya tombol update sesuai
                validateProfileForm();
            });

            // ============================================================
            // 🔍 Fungsi Validasi Form Profile (satu pintu)
            // ============================================================
            function validateProfileForm() {
                let allValid = true;

                $('#formLengkapiProfile [required]').each(function() {
                    const val = $(this).val() || '';
                    if (!val.trim()) {
                        allValid = false;
                        $(this).addClass('is-invalid').removeClass('is-valid');
                    } else {
                        $(this).addClass('is-valid').removeClass('is-invalid');
                    }
                });

                $('#btnSimpanProfile').prop('disabled', !allValid);
            }

            // ============================================================
            // 🎯 Event Realtime Validation (input + textarea + select)
            // ============================================================
            $(document).on('input blur change', '#formLengkapiProfile [required]', function() {
                validateProfileForm();
            });

            // Jalankan validasi awal saat modal dibuka
            $('#lengkapiProfileModal').on('shown.bs.modal', function() {
                validateProfileForm();
            });
            // ============================================================
            // 📌 Saat Modal Dibuka
            // ============================================================
            $('#lengkapiProfileModal').on('shown.bs.modal', function() {
                // Cek setiap field required, kasih class valid/invalid
                $('#formLengkapiProfile [required]').each(function() {
                    $(this).toggleClass('is-valid', !!$(this).val())
                        .toggleClass('is-invalid', !$(this).val());
                });

                // Jalankan validasi awal → update tombol
                validateProfileForm();

                // 🔥 Load Provinsi pertama kali
                loadProvinces("{{ old('province_name', $profile->province_name ?? '') }}");
            });

            // ============================================================
            // 💾 Submit Form Profil
            // ============================================================
            $('#btnSimpanProfile').on('click', function() {
                showLoader("Menyimpan profil...");
                const form = $('#formLengkapiProfile')[0];
                const formData = new FormData(form);

                console.log("===== DEBUG Form Data =====");
                for (let pair of formData.entries()) console.log(pair[0] + ": " + pair[1]);

                axios.post("{{ route('app_ipm.save_profile') }}", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        hideLoader();
                        if (response.data.type === 'success') {
                            $('#lengkapiProfileModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data profil Anda berhasil disimpan.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                showLoader("Cek data verifikasi...");
                                startVerificationCheck();
                            });
                        }
                    })
                    .catch(error => {
                        hideLoader();
                        if (error.response && error.response.status === 422) {
                            let messages = Object.values(error.response.data.errors).flat().join(
                                '<br>');
                            Swal.fire({
                                icon: 'warning',
                                title: 'Validasi Gagal',
                                html: messages
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan saat menyimpan data.'
                            });
                        }
                    });
            });


            // ----------------- Load Pertama (Cek Persetujuan Layanan / Profil) -----------------
            showLoader("Memeriksa persetujuan layanan...");

            if (@json($showTermsModal ?? false)) {
                setTimeout(() => {
                    $('#persetujuanLayananModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    hideLoader();
                }, 500);
            } else {
                checkCompleteProfile();
            }

            // ============================================================
            // ✅ Fungsi cek apakah profil sudah lengkap
            // ============================================================
            function checkCompleteProfile() {
                if (@json($showCompleteProfileModal ?? false)) {
                    // Kalau profil belum lengkap → tampilkan modal isi profil
                    showLoader("Memeriksa profil...");
                    setTimeout(() => {
                        $('#lengkapiProfileModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        // Sembunyikan loader setelah modal benar-benar ditampilkan
                        $('#lengkapiProfileModal').on('shown.bs.modal', hideLoader);
                    }, 500);
                } else {
                    // Kalau profil sudah lengkap → lanjut cek verifikasi
                    showLoader("Cek data verifikasi...");
                    startVerificationCheck();
                }
            }

            // ----------------- Validasi visual input -----------------
            $('#formLengkapiProfile input, #formLengkapiProfile textarea').on('input blur', function() {
                if ($(this).val().trim() === '') {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });
            $('#formLengkapiProfile input, #formLengkapiProfile textarea').trigger('blur');
        });
    </script>




    <script>
        // ==============================
        // FUNGSI CEK PENGECEKAN VERIFICATION MODAL
        // ==============================
        let verificationInterval = null;
        let verificationModalInstance = null;

        // ----------------- Fungsi Global -----------------
        window.fillMissingFields = function(fields) {
            const list = Array.isArray(fields) ?
                fields.map(f => `<li class="list-group-item text-danger">${f} belum lengkap</li>`).join('') :
                '';
            $('#missingFieldsList').html(list);
        }

        window.handleNextStep = function(step) {
            if (step === 'ktp') {
                $('#ktpModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else if (step === 'lokasi') {
                $('#lokasiRumahModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else if (step === 'selfie') {
                $('#selfieModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else if (step === 'verifikasi_muka') {
                $('#verifikasiMukaModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                window.location.href = "{{ route('app.ipm') }}";
            }
        }

        // ----------------- Start Verification -----------------
        window.startVerificationCheck = function() {
            if (window.verificationInterval) return; // cegah interval double

            window.verificationInterval = setInterval(() => {
                axios.get(`${window.APP_URL}/check-verification`)
                    .then(response => {
                        const data = response.data;

                        // ✅ Kalau butuh verifikasi
                        if (data.type === 'success' && data.need_verification) {
                            window.fillMissingFields?.(data.missing_fields);

                            // tampilkan modal hanya kalau ada
                            window.verificationModalInstance?.show();

                            if (data.next_step) {
                                window.handleNextStep?.(data.next_step);
                            }

                            // ✅ Kalau tidak butuh verifikasi
                        } else if (data.type === 'success' && !data.need_verification) {
                            window.verificationModalInstance?.hide?.();
                            clearInterval(window.verificationInterval);
                            window.verificationInterval = null;
                            window.location.href = `${window.APP_URL}/app_ipm`;
                        }
                    })
                    .catch(err => console.error("Error cek verifikasi:", err));
            }, 60000); // cek tiap 1 menit
        }

        $(document).ready(function() {
            const verificationModalEl = document.getElementById('verificationModal');

            if (!verificationModalEl) {
                console.info("ℹ️ Modal #verificationModal tidak ditemukan. Script verifikasi dilewati.");
                return;
            }

            // simpan instance di window supaya bisa diakses global
            window.verificationModalInstance = new bootstrap.Modal(verificationModalEl, {
                backdrop: 'static',
                keyboard: false
            });

            // Tombol "Lengkapi Sekarang"
            $('#btnLengkapi').click(function(e) {
                e.preventDefault();

                axios.get(`${window.APP_URL}/check-verification`)
                    .then(response => {
                        const data = response.data;
                        if (data.type === 'success') {
                            window.verificationModalInstance?.hide?.();
                            window.handleNextStep?.(data.next_step);
                            window.startVerificationCheck();
                        }
                    })
                    .catch(err => console.error("Error check verification:", err));
            });

            // Cek verifikasi pertama kali setelah loader hilang
            $(window).on("load", function() {
                $('#loader').fadeOut(500, function() {
                    axios.get(`${window.APP_URL}/check-verification`)
                        .then(response => {
                            const data = response.data;

                            if (data.type === 'success' && data.need_verification) {
                                window.fillMissingFields?.(data.missing_fields);
                                window.verificationModalInstance?.show();
                            } else {
                                window.verificationModalInstance?.hide?.();
                                window.location.href = `${window.APP_URL}/app_ipm`;
                            }
                        })
                        .catch(err => console.error("Error check verification:", err));
                });
            });
        });
    </script>


    <script>
        // ==============================
        // Variabel global
        // ==============================
        let video = null;
        let canvas = null;
        let takePhotoBtn = null;
        let resetPhotoBtn = null;
        let savePhotoBtn = null;
        let currentStream = null;

        // ==============================
        // Deteksi device mobile
        // ==============================
        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // ==============================
        // Start kamera
        // ==============================
        function startCamera() {
            if (!video || !takePhotoBtn) return;

            // Stop stream lama
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }

            const facingMode = isMobileDevice() ? "environment" : "user";

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: facingMode
                        },
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 480
                        },
                        frameRate: {
                            ideal: 30
                        }
                    },
                    audio: false
                })
                .then(stream => {
                    currentStream = stream;
                    video.srcObject = stream;
                    takePhotoBtn.disabled = true;

                    video.addEventListener("loadedmetadata", () => {
                        video.play();
                        console.log("✅ Video siap:", video.videoWidth, video.videoHeight);
                        takePhotoBtn.disabled = false;
                    }, {
                        once: true
                    });
                })
                .catch(err => {
                    console.error("❌ Error mengakses kamera:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal mengakses kamera',
                        text: 'Pastikan izin kamera sudah diberikan dan perangkat mendukung kamera.'
                    });
                    takePhotoBtn.disabled = true;
                });
        }

        // ==============================
        // Stop kamera
        // ==============================
        function stopCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
            if (video) video.srcObject = null;
        }

        // ==============================
        // Ambil foto
        // ==============================
        function takePhoto() {
            if (!video?.srcObject || !canvas) return;

            const vw = video.videoWidth;
            const vh = video.videoHeight;
            if (vw === 0 || vh === 0) {
                console.warn("Video belum siap");
                return;
            }

            canvas.width = vw;
            canvas.height = vh;
            const ctx = canvas.getContext("2d");
            ctx.drawImage(video, 0, 0, vw, vh);

            if (typeof isImageBlurred === "function" && isImageBlurred(canvas, vw, vh)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Foto buram',
                    text: 'Silakan ambil ulang dengan pencahayaan lebih baik.'
                });
                return;
            }

            canvas.style.display = "block";
            canvas.style.position = "absolute";
            canvas.style.top = "0";
            canvas.style.left = "0";
            canvas.style.width = video.offsetWidth + "px";
            canvas.style.height = video.offsetHeight + "px";

            takePhotoBtn.style.display = "none";
            resetPhotoBtn.style.display = "inline-block";
            savePhotoBtn.style.display = "inline-block";
        }

        // ==============================
        // Reset foto
        // ==============================
        function resetPhoto() {
            if (!canvas) return;

            canvas.style.display = "none";
            resetPhotoBtn.style.display = "none";
            savePhotoBtn.style.display = "none";
            takePhotoBtn.style.display = "inline-block";

            startCamera();
        }

        // ==============================
        // Save foto
        // ==============================
        function savePhoto() {
            if (!canvas) return;

            const dataURL = canvas.toDataURL("image/png");
            showLoader("Memeriksa dan menyimpan KTP...");

            fetch("{{ route('ktp.upload') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        image: dataURL
                    })
                })
                .then(res => res.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Foto KTP berhasil disimpan.'
                            })
                            .then(() => {
                                stopCamera();
                                $('#ktpModal').modal('hide');
                                location.reload();
                            });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan.'
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Tidak bisa mengupload foto.'
                    });
                });
        }

        // ==============================
        // Fungsi bantu cek blur
        // ==============================
        function getAdaptiveThreshold(width, height) {
            const res = width * height;
            if (res <= 1280 * 720) return 100;
            if (res <= 1920 * 1080) return 150;
            return 200;
        }

        function isImageBlurred(canvas, videoWidth, videoHeight) {
            const ctx = canvas.getContext("2d");
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            const gray = [];
            for (let i = 0; i < data.length; i += 4) {
                gray.push((data[i] + data[i + 1] + data[i + 2]) / 3);
            }
            const mean = gray.reduce((a, b) => a + b, 0) / gray.length;
            const variance = gray.reduce((a, b) => a + Math.pow(b - mean, 2), 0) / gray.length;
            const threshold = getAdaptiveThreshold(videoWidth, videoHeight);
            console.log(`Variance: ${variance.toFixed(2)}, Threshold: ${threshold}`);
            return variance < threshold; // true = blur
        }

        // ==============================
        // Inisialisasi saat DOM siap
        // ==============================
        document.addEventListener('DOMContentLoaded', () => {
            // Tunggu sampai #ktpModal tersedia
            const observer = new MutationObserver((mutations, obs) => {
                const modal = document.getElementById("ktpModal");
                if (modal) {
                    initKtpModal(modal);
                    obs.disconnect(); // stop observer
                }
            });
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });

        function initKtpModal(modal) {
            video = modal.querySelector("#videoKTP");
            canvas = modal.querySelector("#canvasKTP");
            takePhotoBtn = modal.querySelector("#takePhoto");
            resetPhotoBtn = modal.querySelector("#resetPhoto");
            savePhotoBtn = modal.querySelector("#savePhoto");

            if (!video || !canvas || !takePhotoBtn || !resetPhotoBtn || !savePhotoBtn) {
                console.error("❌ Element KTP modal tidak ditemukan.");
                return;
            }

            takePhotoBtn.addEventListener("click", takePhoto);
            resetPhotoBtn.addEventListener("click", resetPhoto);
            savePhotoBtn.addEventListener("click", savePhoto);

            $('#ktpModal').on('shown.bs.modal', startCamera);
            $('#ktpModal').on('hidden.bs.modal', stopCamera);
        }
    </script>

    <script>
        let map, marker;

        // ✅ Ambil lokasi dari backend (database)
        const lokasiDb = {
            lat: parseFloat(@json($lokasiLogin['latitude'] ?? 'null')),
            lng: parseFloat(@json($lokasiLogin['longitude'] ?? 'null'))
        };

        // Fungsi untuk inisialisasi map
        function initMap(lat, lng, zoom = 16) {
            map = L.map("mapLokasi", {
                center: [lat, lng],
                zoom: zoom,
                minZoom: 3,
                maxZoom: 22
            });

            // Tambahkan tile layer OSM
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "&copy; OpenStreetMap contributors",
                maxZoom: 22
            }).addTo(map);

            // Pasang marker awal
            pasangMarker(lat, lng, zoom);

            // Klik di peta → pindah marker
            map.on("click", function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                pasangMarker(lat, lng, map.getZoom());
            });
        }

        // Fungsi untuk pasang/update marker
        function pasangMarker(lat, lng, zoom = 16) {
            map.setView([lat, lng], zoom);

            if (!marker) {
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                marker.on("dragend", function(e) {
                    const {
                        lat,
                        lng
                    } = e.target.getLatLng();
                    console.log("Marker dipindah:", lat, lng);
                });
            } else {
                marker.setLatLng([lat, lng]);
            }

            marker.bindPopup("Lokasi rumah Anda").openPopup();
        }

        // Deteksi lokasi otomatis saat modal dibuka
        $('#lokasiRumahModal').on('shown.bs.modal', function() {
            if (!map) {
                if (lokasiDb.lat && lokasiDb.lng) {
                    // ✅ 1. Pakai lokasi database
                    initMap(lokasiDb.lat, lokasiDb.lng, 16);
                } else if (navigator.geolocation) {
                    // ✅ 2. Jika database kosong, gunakan lokasi GPS browser
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            initMap(position.coords.latitude, position.coords.longitude, 17);
                        },
                        function(error) {
                            console.error("Gagal ambil lokasi GPS:", error);
                            alert("Tidak bisa mengambil lokasi Anda. Silakan aktifkan GPS.");
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    // ✅ 3. Jika browser tidak support GPS
                    alert("Browser Anda tidak mendukung geolokasi.");
                }
            } else {
                // Refresh ukuran map agar tidak blank
                setTimeout(() => map.invalidateSize(), 300);
            }
        });

        // Simpan lokasi ke server
        $('#btnSaveLokasi').click(function() {
            if (!marker) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih lokasi dulu di peta.'
                });
                return;
            }

            const {
                lat,
                lng
            } = marker.getLatLng();
            console.log("Lokasi disimpan:", lat, lng);

            axios.post("{{ route('lokasi.upload') }}", {
                    latitude: lat,
                    longitude: lng
                })
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Lokasi berhasil disimpan!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Tutup modal dan reload halaman
                        $('#lokasiRumahModal').modal('hide');
                        location.reload();
                    });
                })
                .catch(err => {
                    console.error(err.response?.data || err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menyimpan lokasi. Silakan coba lagi.'
                    });
                });
        });



        // ===================== SELFIE OPTIMAL =====================
        // ============================================================

        let currentStreamSelfie = null; // simpan stream kamera

        // ===================== 1️⃣ Aktifkan Kamera =====================
        async function startSelfieCamera() {
            const video = document.getElementById("videoSelfie");

            try {
                // request akses kamera depan
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "user"
                    }
                });

                currentStreamSelfie = stream;
                video.srcObject = stream;
                await video.play();
            } catch (err) {
                console.error("❌ Error akses kamera:", err);
                Swal.fire("Error", "Tidak bisa mengakses kamera.", "error");
            }
        }

        // ===================== 2️⃣ Ambil Foto =====================
        $("#takeSelfie").off("click").on("click", function() {
            const video = document.getElementById("videoSelfie");
            const canvas = document.getElementById("canvasSelfie");
            const overlay = document.getElementById("selfieFrameOverlay");

            // Ukuran asli video → canvas
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext("2d");

            // Copy frame langsung
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // ✅ Tambahkan efek lebih terang & tajam
            ctx.filter = "brightness(1.2) contrast(1.15) saturate(1.1)";
            ctx.drawImage(canvas, 0, 0);

            // Tampilkan hasil canvas, sembunyikan video
            canvas.style.display = "block";
            video.style.display = "none";
            overlay.style.display = "none";

            // Ubah tombol
            $("#takeSelfie").hide();
            $("#resetSelfie, #saveSelfie").show();
        });
        // ===================== 3️⃣ Reset Foto =====================
        $("#resetSelfie").off("click").on("click", function() {
            const canvas = document.getElementById("canvasSelfie");
            const video = document.getElementById("videoSelfie");
            const overlay = document.getElementById("selfieFrameOverlay");

            // Kosongkan canvas
            const ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Balikin ke mode kamera
            canvas.style.display = "none";
            video.style.display = "block";
            overlay.style.display = "block";

            // Ubah tombol
            $("#takeSelfie").show();
            $("#resetSelfie, #saveSelfie").hide();
        });

        // ===================== 4️⃣ Simpan Foto =====================
        $("#saveSelfie").off("click").on("click", function() {
            const canvas = document.getElementById("canvasSelfie");
            const dataURL = canvas.toDataURL("image/jpeg", 0.8); // kompres 80%

            axios.post("{{ route('selfie.upload') }}", {
                    image: dataURL
                })
                .then(res => {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: "Selfie berhasil disimpan!"
                    }).then(() => {
                        // kalau pakai modal
                        $("#selfieModal").modal("hide");
                        location.reload();
                    });
                })
                .catch(err => {
                    console.error("❌ Error upload selfie:", err);
                    Swal.fire("Gagal", "Gagal menyimpan selfie. Silakan coba lagi.", "error");
                });
        });

        // ===================== 5️⃣ Modal Dibuka =====================
        $("#selfieModal").on("shown.bs.modal", async function() {
            await startSelfieCamera();
        });

        // ===================== 6️⃣ Modal Ditutup =====================
        $("#selfieModal").on("hidden.bs.modal", function() {
            if (currentStreamSelfie) {
                currentStreamSelfie.getTracks().forEach(track => track.stop());
                currentStreamSelfie = null;
            }

            // Reset UI
            $("#takeSelfie").show();
            $("#resetSelfie, #saveSelfie").hide();
            $("#videoSelfie").show();
            $("#canvasSelfie").hide();
            $("#selfieFrameOverlay").show();
        });



        // =============================================================
        // 📌 SCRIPT VERIFIKASI WAJAH – FINAL (Dengan Progress Loader)
        // =============================================================

        /**
         * =============================================================
         * 🔹 VARIABEL GLOBAL
         * =============================================================
         */
        let currentStreamVerifikasi = null; // Menyimpan stream kamera
        let actionIndex = 0; // Indeks aksi yang sedang dijalankan
        let allCaptures = []; // Menyimpan hasil capture blob
        let modelLoaded = false; // Status apakah model sudah diload
        let registeredDescriptor = null; // Descriptor wajah user dari selfie
        let verificationFailed = false; // Flag jika verifikasi gagal
        let isResetting = false; // Flag jika sedang reset
        let isCapturing = false; // Flag jika sedang capture
        let modelType = "tiny"; // Model faceapi yang digunakan
        const MODEL_PATH = "/assets/model"; // Lokasi model

        // Daftar aksi gerakan
        const actions = [{
                text: "Hadap ke Kiri",
                key: "left"
            },
            {
                text: "Hadap ke Kanan",
                key: "right"
            },
            {
                text: "Angkat Kepala",
                key: "up"
            },
            {
                text: "Buka Mulut",
                key: "mouth"
            }
        ];

        // Threshold minimal akurasi untuk tiap aksi
        const ACTION_THRESHOLDS = {
            left: 50,
            right: 50,
            up: 50,
            mouth: 50
        };

        /**
         * =============================================================
         * 🔹 Text-to-Speech – Membacakan instruksi aksi
         * =============================================================
         */
        function speakInstruction(text) {
            try {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = "id-ID";
                utterance.rate = 1;
                speechSynthesis.cancel(); // hentikan suara sebelumnya
                speechSynthesis.speak(utterance);
            } catch (err) {
                console.warn("Speech synthesis tidak didukung", err);
            }
        }

        /**
         * =============================================================
         * 🔹 Reset Verifikasi
         * Membersihkan semua variabel & state agar bisa mulai ulang
         * =============================================================
         */
        function resetVerification(stopCamera = true) {
            if (isResetting) return;
            isResetting = true;

            if (stopCamera && currentStreamVerifikasi) {
                currentStreamVerifikasi.getTracks().forEach(track => track.stop());
                currentStreamVerifikasi = null;
            }

            actionIndex = 0;
            allCaptures = [];
            verificationFailed = false;
            isCapturing = false;

            const actionTextEl = document.getElementById("actionText");
            if (actionTextEl) actionTextEl.innerText = "";

            const startBtn = document.getElementById("startVerifikasi");
            if (startBtn) startBtn.disabled = false;

            setTimeout(() => {
                isResetting = false;
            }, 400);
        }

        /**
         * =============================================================
         * 🔹 Load Face Models
         * Memuat model face-api dan update progress → 25%
         * =============================================================
         */
        async function loadFaceModels() {
            if (modelLoaded) return;
            try {
                const progressEl = document.getElementById("loadingProgress");

                const models = [{
                        net: faceapi.nets.tinyFaceDetector,
                        path: `${MODEL_PATH}/tiny_face_detector`
                    },
                    {
                        net: faceapi.nets.faceRecognitionNet,
                        path: `${MODEL_PATH}/face_recognition`
                    },
                    {
                        net: faceapi.nets.faceLandmark68Net,
                        path: `${MODEL_PATH}/face_landmark_68`
                    }
                ];

                for (let i = 0; i < models.length; i++) {
                    await models[i].net.loadFromUri(models[i].path);
                }

                modelLoaded = true;
                if (progressEl) progressEl.innerText = `25%`;
                console.log("✅ Semua model Face-API berhasil dimuat.");
            } catch (error) {
                console.error("Gagal memuat model:", error);
                Swal.fire("Error", "Gagal memuat model Face-API.", "error");

                const loader = document.getElementById("loadingModels");
                if (loader) loader.style.display = "none";
            }
        }

        /**
         * =============================================================
         * 🔹 Load Selfie User Descriptor
         * Membuat descriptor wajah user dari selfie → progress 50%
         * =============================================================
         */
        async function loadRegisteredDescriptor(selfieUrl) {
            if (!selfieUrl) {
                verificationFailed = true;
                Swal.fire("Error", "Selfie user tidak ditemukan.", "error");
                $("#verifikasiMukaModal").modal("hide");
                return;
            }
            try {
                const img = await faceapi.fetchImage(selfieUrl);
                const detection = await faceapi
                    .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({
                        inputSize: 128,
                        scoreThreshold: 0.5
                    }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) throw new Error("Wajah di selfie tidak terdeteksi!");
                registeredDescriptor = detection.descriptor;
                console.log("✅ Descriptor selfie user berhasil dibuat.");

                const progressEl = document.getElementById("loadingProgress");
                if (progressEl) progressEl.innerText = `50%`;
            } catch (err) {
                console.error("Gagal membuat descriptor:", err);
                Swal.fire("Error", err.message, "error");
                $("#verifikasiMukaModal").modal("hide");
                verificationFailed = true;
            }
        }

        /**
         * =============================================================
         * 🔹 Start Kamera
         * Menghidupkan kamera user → update progress 100% & tutup loader
         * =============================================================
         */
        async function startCameraVerifikasi() {
            try {
                const video = document.getElementById("videoVerifikasi");
                const startBtn = document.getElementById("startVerifikasi");
                if (startBtn) startBtn.disabled = true;

                currentStreamVerifikasi = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "user",
                        width: 320,
                        height: 240,
                        frameRate: 10
                    },
                    audio: false
                });

                video.srcObject = currentStreamVerifikasi;

                await new Promise((resolve) => {
                    video.onloadedmetadata = () => {
                        video.play().then(resolve).catch(err => {
                            console.error("Gagal play video:", err);
                            resolve();
                        });
                    };
                });

                const progressEl = document.getElementById("loadingProgress");
                if (progressEl) progressEl.innerText = `100%`;

                const loader = document.getElementById("loadingModels");
                if (loader) loader.style.display = "none";

                if (startBtn) startBtn.disabled = false;
                console.log("✅ Kamera verifikasi siap digunakan.");
            } catch (err) {
                console.error("Gagal akses kamera:", err);
                Swal.fire("Error", "Tidak bisa mengakses kamera.", "error");
                verificationFailed = true;

                const loader = document.getElementById("loadingModels");
                if (loader) loader.style.display = "none";
            }
        }

        /**
         * =============================================================
         * 🔹 Capture Wajah
         * Mengambil gambar dari kamera & menghitung akurasi wajah
         * =============================================================
         */
        async function captureFace() {
            const video = document.getElementById("videoVerifikasi");
            const canvas = document.getElementById("canvasVerifikasi");
            const ctx = canvas.getContext("2d", {
                willReadFrequently: true
            });

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const detection = await faceapi
                .detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions({
                    inputSize: 128,
                    scoreThreshold: 0.5
                }))
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) return null;

            const distance = faceapi.euclideanDistance(detection.descriptor, registeredDescriptor);
            const accuracy = Math.round((1 - distance) * 100);

            return new Promise(resolve => {
                canvas.toBlob(blob => resolve({
                    blob,
                    accuracy
                }), "image/jpeg", 0.7);
            });
        }

        /**
         * =============================================================
         * 🔹 Jalankan Aksi Step by Step
         * Menjalankan instruksi gerakan wajah secara berurutan
         * =============================================================
         */
        async function nextAction() {
            if (verificationFailed || isCapturing) return;
            isCapturing = true;

            const startBtn = document.getElementById("startVerifikasi");
            if (startBtn) startBtn.disabled = true;

            if (actionIndex >= actions.length) {
                if (allCaptures.length === actions.length * 2) {
                    await Swal.fire({
                        title: "Selesai",
                        text: "Semua aksi berhasil, data akan diproses.",
                        icon: "success"
                    });
                    Swal.fire({
                        title: "Memproses...",
                        text: "Mohon tunggu...",
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    await uploadCaptures();
                } else resetVerification(false);
                isCapturing = false;
                return;
            }

            const currentAction = actions[actionIndex];
            speakInstruction(currentAction.text);

            for (let i = 0; i < 2; i++) {
                await new Promise(r => setTimeout(r, 1000));
                const capture = await captureFace();
                if (!capture) {
                    verificationFailed = true;
                    await Swal.fire("Gagal", `Wajah tidak terdeteksi pada aksi "${currentAction.text}".`, "error");
                    resetVerification(false);
                    isCapturing = false;
                    return;
                }

                const threshold = ACTION_THRESHOLDS[currentAction.key] || 50;
                console.log(
                    `📸 Aksi: ${currentAction.text} | Capture ${i+1} | Akurasi: ${capture.accuracy}% | Threshold: ${threshold}%`
                );

                if (capture.accuracy < threshold) {
                    verificationFailed = true;
                    await Swal.fire("Gagal",
                        `Verifikasi "${currentAction.text}" tidak valid (akurasi: ${capture.accuracy}%).`, "error");
                    resetVerification(false);
                    isCapturing = false;
                    return;
                }
                allCaptures.push(capture.blob);
            }

            actionIndex++;
            isCapturing = false;
            nextAction();
        }

        /**
         * =============================================================
         * 🔹 Upload Captures
         * Mengirim semua hasil capture ke server
         * =============================================================
         */
        async function uploadCaptures() {
            const formData = new FormData();
            allCaptures.forEach((blob, i) => formData.append("images[]", blob, `capture_${i}.jpg`));

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
                const res = await axios.post("{{ route('verifikasi.upload') }}", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                        "X-CSRF-TOKEN": csrfToken
                    }
                });
                Swal.close();

                if (res.data.status === "verified") {
                    await Swal.fire("Berhasil", res.data.message, "success");
                    $("#verifikasiMukaModal").modal("hide");
                    location.reload();
                } else {
                    await Swal.fire("Gagal", res.data.message || "Verifikasi gagal", "error");
                    resetVerification(false);
                }
            } catch (err) {
                console.error("Upload error:", err.response?.data || err.message);
                Swal.close();
                Swal.fire("Error", "Gagal mengupload foto.", "error");
                resetVerification(false);
            }
        }

        /**
         * =============================================================
         * 🔹 Event Modal
         * Menjalankan alur proses step-by-step dengan loader progresif
         * =============================================================
         */
        $("#verifikasiMukaModal").on("shown.bs.modal", async function() {
            const loader = document.getElementById("loadingModels");
            const progressEl = document.getElementById("loadingProgress");

            if (loader) loader.style.display = "flex"; // Tampilkan loader
            if (progressEl) progressEl.innerText = `0%`;

            if (!modelLoaded) await loadFaceModels();
            if (!modelLoaded) return;

            const selfieUrl = @json($selfieUrl ?? null);
            await loadRegisteredDescriptor(selfieUrl);
            if (verificationFailed) {
                if (loader) loader.style.display = "none";
                return;
            }

            // ✅ Update progress → 75% sebelum kamera
            if (progressEl) progressEl.innerText = `75%`;

            await startCameraVerifikasi();
            if (verificationFailed) {
                if (loader) loader.style.display = "none";
                return;
            }

            document.getElementById("startVerifikasi").onclick = () => {
                if (!isCapturing) {
                    actionIndex = 0;
                    allCaptures = [];
                    verificationFailed = false;
                    nextAction();
                }
            };

            document.getElementById("resetVerifikasi").onclick = () => {
                resetVerification();
                Swal.fire("Info", "Verifikasi diulang dari awal.", "info");
            };

            document.getElementById("saveVerifikasi").onclick = () => {
                if (verificationFailed || allCaptures.length !== actions.length * 2) {
                    Swal.fire("Gagal", "Verifikasi gagal atau belum lengkap.", "error");
                    return;
                }
                uploadCaptures();
            };
        });
    </script>
