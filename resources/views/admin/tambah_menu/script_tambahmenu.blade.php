<script>
    $(function() {
        console.log("Script loaded ✅");

        /**
         * Event: klik tombol Tambah User
         * - Buka modal #tambah-user
         * - Bind event click tombol .simpan di dalam modal
         */

        // ============================================
        // 🔑 BUAT MENU
        // ============================================

        $('body').on('click', '.tambah_menu', function(e) {
            e.preventDefault();

            // Tampilkan modal tambah user
            $('#tambahMenuModal').modal('show');

            // Hindari double binding (pakai .off().on())
            $('.simpan').off('click').on('click', function() {
                var name_menu = $('#name_menu').val();
                var icon = $('#icon').val();
                var parent_id = $('#parent_id').val();
                var route = $('#route').val();

                console.log("📌 Data yang diambil:", {
                    name_menu: name_menu,
                    icon: icon,
                    parent_id: parent_id,
                    route: route
                });

                if (!name_menu || !icon || !parent_id || !route) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Tidak Lengkap',
                        text: 'Semua field wajib diisi!'
                    });
                    return;
                }
                console.log("✅ Semua input valid, siap dikirim ke server...");

                $.ajax({
                    url: '{{ route('setting.menu_store') }}',
                    type: 'POST',
                    data: {
                        name_menu: name_menu,
                        icon: icon,
                        parent_id: parent_id,
                        route: route,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        console.log("⏳ Mengirim data ke server...");
                        showLoader("Menyimpan menu...");
                    },
                    success: function(response) {
                        hideLoader();
                        console.log("✅ Respon server:", response);

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
                                    $('#tambahMenuModal').modal('hide');
                                    $('#name_menu, #icon, #parent_id, #route')
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
                        console.error("❌ Server error:", xhr.responseText);
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

        // ============================================
        // 🔑 EDIT MENU
        // ============================================
        $('body').on('click', '.edit_menu', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            $.ajax({
                url: '/setting/menuEditAjax/' + id,
                type: 'GET',
                beforeSend: function() {
                    showLoader("Mengambil data menu...");
                },
                success: function(response) {
                    hideLoader();
                    console.log("✅ Data menu:", response);

                    if (!response.result) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Data menu tidak ditemukan.'
                        });
                        return;
                    }

                    // Buka modal edit menu
                    $('#editMenuModal').modal('show');

                    // Isi form input
                    $('#id').val(response.result.id);
                    $('#name_menu_edit').val(response.result.name);
                    $('#icon_edit').val(response.result.icon);
                    $('#route_edit').val(response.result.route);

                    // Bersihkan dan isi ulang dropdown parent_id
                    $('#parent_id_edit').empty();
                    $('#parent_id_edit').append(
                        '<option value="">Pilih Parent Menu</option>');

                    $.each(response.menus, function(index, menu) {
                        let selected = menu.id == response.result.parent_id ?
                            'selected' : '';
                        $('#parent_id_edit').append(
                            `<option value="${menu.id}" ${selected}>${menu.name}</option>`
                        );
                    });

                },
                error: function(xhr) {
                    hideLoader();
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memuat data menu.'
                    });
                }
            });
        });

        // ============================================
        // 🔄 UPDATE MENU
        // ============================================
        $('body').on('click', '.edit', function() {
            const id = $('#id').val();
            const name_menu = $('#name_menu_edit').val();
            const icon = $('#icon_edit').val();
            const parent_id = $('#parent_id_edit').val();
            const route = $('#route_edit').val();

            console.log("📌 Mengirim update menu:", {
                id,
                name_menu,
                icon,
                parent_id,
                route
            });

            if (!name_menu || !icon || !route) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Tidak Lengkap',
                    text: 'Nama, Icon, dan Route wajib diisi!'
                });
                return;
            }

            $.ajax({
                url: '/setting/menuUpdateAjax/' + id,
                type: 'PUT',
                data: {
                    name_menu: name_menu,
                    icon: icon,
                    parent_id: parent_id,
                    route: route,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    console.log("⏳ Menyimpan perubahan...");
                    showLoader("Menyimpan perubahan...");
                },
                success: function(response) {
                    hideLoader();
                    console.log("✅ Respon server:", response);

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        }).then(() => {
                            $('#editMenuModal').modal('hide');
                            // Reload halaman untuk update tabel
                            location.reload();
                        });
                    } else if (response.status === 'validation') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal!',
                            html: response.message.join('<br>')
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || xhr.statusText
                    });
                }
            });
        });


        // ============================================
        // 🔄 DELETE MENU
        // ============================================

        // ========== DELETE MENU (Tampilkan Modal) ==========
        $('body').on('click', '.delete_menu', function(e) {
            e.preventDefault();

            const id = $(this).data('id');
            const urlFetch = `/setting/fetchdelete/${id}`; // <-- pastikan path sesuai route

            console.log("📌 Mengambil data menu:", {
                id,
                urlFetch
            });

            $.ajax({
                url: urlFetch,
                type: 'GET',
                beforeSend: function() {
                    showLoader("Mengambil data menu...");
                },
                success: function(response) {
                    hideLoader();
                    console.log(response); // ✅ cek di console

                    if (response.status === 'success') {
                        $('#delete-menu').modal('show');
                        $('#id').val(response.result.id);
                        $('#name_delete').val(response.result.name);
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    Swal.fire('Error', xhr.responseJSON?.message ||
                        'Gagal memuat data menu.', 'error');
                }
            });
        });

        // // ========== KONFIRMASI DELETE ==========
        $('body').on('click', '.hapus', function() {
            let id = $('#id').val();
            let urlDelete = `/setting/hapusMenu/${id}`; // misal route DELETE /menus/{id}

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Menu akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: urlDelete,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            showLoader("Menghapus menu...");
                        },
                        success: function(response) {
                            hideLoader();

                            if (response.status === 'success') {
                                Swal.fire('Berhasil!', response.message, 'success')
                                    .then(() => {
                                        $('#delete-menu').modal('hide');
                                        location.reload();
                                    });
                            } else if (response.type === 'menu_in_use') {
                                Swal.fire('Menu Digunakan!', response.message,
                                    'warning');
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            hideLoader();
                            Swal.fire('Error', xhr.responseJSON?.message ||
                                'Gagal menghapus menu.', 'error');
                        }
                    });
                }
            });
        });


        // ============================================
        // 🔑 BUAT PERMISSIONS
        // ============================================

        $('body').on('click', '.tambah_permissions', function(e) {
            e.preventDefault();

            // Tampilkan modal tambah user
            $('#tambahPermissionsModal').modal('show');

            // Hindari double binding (pakai .off().on())
            $('.simpan').off('click').on('click', function() {
                var name_permissions = $('#name_permissions').val();
                var description = $('#description').val();


                console.log("📌 Data yang diambil:", {
                    name_permissions: name_permissions,
                    description: description,

                });

                if (!name_permissions || !description) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Tidak Lengkap',
                        text: 'Semua field wajib diisi!'
                    });
                    return;
                }
                console.log("✅ Semua input valid, siap dikirim ke server...");

                $.ajax({
                    url: '{{ route('setting.permission_store') }}',
                    type: 'POST',
                    data: {
                        name_permissions: name_permissions,
                        description: description,

                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        console.log("⏳ Mengirim data ke server...");
                        showLoader("Menyimpan menu...");
                    },
                    success: function(response) {
                        hideLoader();
                        console.log("✅ Respon server:", response);

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
                                    $('#tambahPermissionsModal').modal(
                                        'hide');
                                    $('#name_permissions, #description')
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
                        console.error("❌ Server error:", xhr.responseText);
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

    });


    // ============================================
    // 🔑 EDIT PERMISSIONS (Buka Modal & Isi Form)
    // ============================================
    $('body').on('click', '.btn-edit-permissions', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        $.ajax({
            url: '/setting/permissionsEditAjax/' + id,
            type: 'GET',
            beforeSend: function() {
                showLoader("Mengambil data permission...");
            },
            success: function(response) {
                hideLoader();

                if (response.status !== 'success' || !response.result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Data permission tidak ditemukan.'
                    });
                    return;
                }

                // Buka modal edit
                $('#editPermissionsModal').modal('show');

                // Isi form input
                $('#id').val(response.result.id);
                $('#name_permissions_edit').val(response.result.name);
                $('#description_edit').val(response.result.description);
            },
            error: function(xhr) {
                hideLoader();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memuat data permission.'
                });
            }
        });
    });

    // ============================================
    // 🔄 UPDATE PERMISSIONS (Submit Update Data)
    // ============================================
    $('body').on('click', '.btn-save-permissions', function() {
        const id = $('#id').val();
        const name_permissions_edit = $('#name_permissions_edit').val();
        const description_edit = $('#description_edit').val();

        if (!name_permissions_edit || !description_edit) {
            Swal.fire({
                icon: 'warning',
                title: 'Form Tidak Lengkap',
                text: 'Nama dan Deskripsi wajib diisi!'
            });
            return;
        }

        $.ajax({
            url: '/setting/permissionsUpdateAjax/' + id,
            type: 'PUT',
            data: {
                name_permissions_edit,
                description_edit,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                showLoader("Menyimpan perubahan...");
            },
            success: function(response) {
                hideLoader();
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message
                    }).then(() => {
                        $('#editPermissionsModal').modal('hide');
                        location.reload();
                    });
                } else if (response.status === 'validation') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal!',
                        html: response.message.join('<br>')
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                hideLoader();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || xhr.statusText
                });
            }
        });
    });

    // ============================================
    // 🔄 FETCH DELETE MENU
    // ============================================

    $('body').on('click', '.btn-delete-permissions', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const urlFetch = `/setting/permissionsfetchdelete/${id}`; // <-- pastikan path sesuai route

        console.log("📌 Mengambil data menu:", {
            id,
            urlFetch
        });

        $.ajax({
            url: urlFetch,
            type: 'GET',
            beforeSend: function() {
                showLoader("Mengambil data menu...");
            },
            success: function(response) {
                hideLoader();
                console.log(response); // ✅ cek di console

                if (response.status === 'success') {
                    $('#delete-permissions').modal('show');
                    $('#id').val(response.result.id);
                    $('#name_delete_permissions').val(response.result.name);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                hideLoader();
                Swal.fire('Error', xhr.responseJSON?.message ||
                    'Gagal memuat data menu.', 'error');
            }
        });
    });



    // ============================================
    // 🔄 KONFIRMASI DELETE MENU
    // ============================================

    $('body').on('click', '.btn-hapus-permissions', function() {
        let id = $('#id').val();
        let urlDelete = `/setting/hapusPermissions/${id}`; // misal route DELETE /menus/{id}

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Menu akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: urlDelete,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        showLoader("Menghapus menu...");
                    },
                    success: function(response) {
                        hideLoader();

                        if (response.status === 'success') {
                            Swal.fire('Berhasil!', response.message, 'success')
                                .then(() => {
                                    $('#delete-permissions').modal('hide');
                                    location.reload();
                                });
                        } else if (response.type === 'menu_in_use') {
                            Swal.fire('Menu Digunakan!', response.message,
                                'warning');
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        Swal.fire('Error', xhr.responseJSON?.message ||
                            'Gagal menghapus menu.', 'error');
                    }
                });
            }
        });
    });
</script>
