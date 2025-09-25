<script>
    /**
     * 🔑 Script untuk toggle show/hide password
     * - Target input: #password
     * - Target icon: <i class="fa fa-eye"></i>
     */
    $(document).on('click', '#togglePassword', function() {
        let passwordInput = $('#password');
        let icon = $(this).find('i');

        // Kalau masih type=password → ubah ke text
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });


    // Toggle password untuk edit user
    $(document).on('click', '#togglePasswordEdit', function() {
        let passwordInput = $('#password_edit');
        let icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });


    // ============================================
    // ✅ Loader Handler
    // ============================================
    function showLoader(text = "Mohon Tunggu...") {
        document.getElementById("loader-text").innerText = text;
        document.getElementById("proses-loader").style.display = "flex";
    }

    function hideLoader() {
        document.getElementById("proses-loader").style.display = "none";
    }


    const routeFetchDelete = "{{ route('user.fetch.delete.ajax', ':id') }}";
    const routeDeleteUser = "{{ route('user.delete.ajax', ':id') }}";

    $(function() {
        console.log("Script loaded ✅");

        /**
         * Event: klik tombol Tambah User
         * - Buka modal #tambah-user
         * - Bind event click tombol .simpan di dalam modal
         */
        $('body').on('click', '.tambah-user', function(e) {
            e.preventDefault();

            // Tampilkan modal tambah user
            $('#tambah-user').modal('show');

            // Hindari double binding (pakai .off().on())
            $('.simpan').off('click').on('click', function() {
                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var role_id = $('#role').val();

                if (!name || !email || !password || !role_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Tidak Lengkap',
                        text: 'Semua field wajib diisi!'
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('user.store.ajax') }}',
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        role_id: role_id,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        // ✅ Munculkan loader sebelum request dikirim
                        showLoader("Menyimpan user...");
                    },
                    success: function(response) {
                        hideLoader(); // ⬅️ tutup loader dulu

                        switch (response.status) {
                            case 'validation':
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal!',
                                    html: response.message.join('<br>')
                                });
                                break;

                            case 'error':
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                                break;

                            case 'success':
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message
                                }).then(() => {
                                    $('#tambah-user').modal('hide');
                                    $('#name, #email, #password, #role')
                                        .val('');
                                    location.reload();
                                });
                                break;

                            default:
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Respon Tidak Dikenali',
                                    text: JSON.stringify(response)
                                });
                                break;
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: xhr.responseJSON?.message || xhr
                                .statusText
                        });
                        hideLoader();
                    }
                });
            });
        });


        //-DELETE-USER
        $('body').on('click', '.delete-user', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            let urlFetch = routeFetchDelete.replace(':id', id);

            // Ambil detail user sebelum menghapus
            $.ajax({
                url: urlFetch,
                type: 'GET',
                success: function(response) {
                    hideLoader(); // Tutup loader setelah response
                    if (response.result) {
                        $('#delete-user').modal('show');
                        $('#id').val(response.result.id);
                        $('#name_delete').val(response.result.name);
                        $('#role_name_delete').val(response.result.role_name);

                        $('.hapus').off('click').on('click', function() {
                            let userId = $('#id').val();
                            let urlDelete = routeDeleteUser.replace(':id', userId);

                            Swal.fire({
                                title: 'Yakin ingin menghapus pengguna ini?',
                                text: "Tindakan ini tidak dapat dibatalkan!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Ya, hapus!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: urlDelete,
                                        type: 'DELETE',
                                        data: {
                                            _token: $(
                                                'meta[name="csrf-token"]'
                                            ).attr(
                                                'content')
                                        },
                                        beforeSend: function() {
                                            showLoader(
                                                "Menghapus user..."
                                            );
                                        },
                                        success: function(
                                            response) {
                                            hideLoader();
                                            Swal.fire(
                                                'Terhapus!',
                                                response
                                                .success,
                                                'success');
                                            $('#delete-user')
                                                .modal('hide');
                                            location
                                                .reload(); // reload halaman setelah hapus
                                        },
                                        error: function(xhr) {
                                            hideLoader();
                                            let msg = xhr
                                                .responseJSON
                                                ?.error ||
                                                'Terjadi kesalahan saat menghapus.';
                                            Swal.fire('Gagal!',
                                                msg, 'error'
                                            );
                                        }
                                    });
                                }
                            });
                        });
                    }
                },
                error: function() {
                    hideLoader();
                    Swal.fire('Error', 'Gagal memuat data user.', 'error');
                }
            });
        });


        // ============================================
        // 🔑 EDIT USER
        // ============================================
        $('body').on('click', '.edit-user', function(e) {
            e.preventDefault();

            const id = $(this).data('id');


            $.ajax({
                url: '/users/userEditAjax/' + id,
                type: 'GET',
                success: function(response) {
                    hideLoader();

                    if (!response.result) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Data pengguna tidak ditemukan.'
                        });
                        return;
                    }

                    // Tampilkan modal dan isi form
                    $('#edit-user').modal('show');
                    $('#id').val(response.result.id);
                    $('#name_edit').val(response.result.name);
                    $('#email_edit').val(response.result.email);
                    $('#password_edit').val(''); // Kosongkan password
                    $('#role_name_edit').val(response.result.role_id);

                    // Bind tombol Simpan di modal
                    $('#edit-user .edit').off('click').on('click', function() {
                        const name = $('#name_edit').val();
                        const email = $('#email_edit').val();
                        const password = $('#password_edit').val();
                        const role_id = $('#role_name_edit').val();

                        if (!name || !email || !role_id) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Form Tidak Lengkap',
                                text: 'Nama, Email, dan Role wajib diisi!'
                            });
                            return;
                        }

                        $.ajax({
                            url: '/users/userUpdateAjax/' + id,
                            type: 'PUT',
                            data: {
                                id: id,
                                name: name,
                                email: email,
                                password: password,
                                role_id: role_id,
                                _token: $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            beforeSend: function() {
                                showLoader("Menyimpan perubahan...");
                            },
                            success: function(response) {
                                hideLoader();

                                switch (response.status) {
                                    case 'validation':
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Validasi Gagal!',
                                            html: response
                                                .message.join(
                                                    '<br>')
                                        });
                                        break;

                                    case 'error':
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: response
                                                .message
                                        });
                                        break;

                                    case 'success':
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: response
                                                .message
                                        }).then(() => {
                                            $('#edit-user')
                                                .modal('hide');
                                            // Reload halaman untuk update tabel
                                            location.reload();
                                        });
                                        break;

                                    default:
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Tidak Diketahui',
                                            text: JSON
                                                .stringify(
                                                    response)
                                        });
                                        break;
                                }
                            },
                            error: function(xhr) {
                                hideLoader();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Server Error',
                                    text: xhr.responseJSON
                                        ?.message || xhr
                                        .statusText
                                });
                            }
                        });
                    });
                },
                error: function(xhr) {
                    hideLoader();
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memuat data pengguna.'
                    });
                }
            });
        });
    });


    // ============================================
    // 🚫 Nonaktifkan User
    // ============================================
    $('body').on('click', '.nonaktifkan-user', function(e) {
        var id = $(this).data('id');
        e.preventDefault();

        Swal.fire({
            title: 'Nonaktifkan akun ini?',
            text: "User tidak akan bisa login.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Nonaktifkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/nonAktif/' + id,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        showLoader("Menonaktifkan user...");
                    },
                    success: function(response) {
                        hideLoader();
                        Swal.fire('Berhasil!', response.success, 'success')
                            .then(() => location.reload());
                    },
                    error: function() {
                        hideLoader();
                        Swal.fire('Gagal!', 'User gagal di-nonaktifkan.', 'error');
                    }
                });
            }
        });
    });


    // ============================================
    // ✅ Aktifkan User
    // ============================================
    $('body').on('click', '.aktifkan-user', function(e) {
        var id = $(this).data('id');
        e.preventDefault();

        Swal.fire({
            title: 'Aktifkan akun ini?',
            text: "User bisa login kembali.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Aktifkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/Aktif/' + id,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        showLoader("Mengaktifkan user...");
                    },
                    success: function(response) {
                        hideLoader();
                        Swal.fire('Berhasil!', response.success, 'success')
                            .then(() => location.reload());
                    },
                    error: function() {
                        hideLoader();
                        Swal.fire('Gagal!', 'User gagal diaktifkan.', 'error');
                    }
                });
            }
        });
    });

</script>
