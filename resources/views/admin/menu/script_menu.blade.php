<script>
    $(document).ready(function() {
        // setup csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2').on('change', function() {
            let roleId = $(this).val();
            if (!roleId) return;

            $.get('/' + roleId + '/access', function(data) {
                let menuHtml = '';
                let permHtml = '';

                // build menu table
                data.menus.forEach(menu => {
                    let checked = data.role.menus.some(rm => rm.id === menu.id) ?
                        'checked' : '';
                    menuHtml += `
                    <tr>
                        <td><input type="checkbox" name="menu_ids[]" value="${menu.id}" ${checked}></td>
                        <td><i class="${menu.icon}"></i> <b>${menu.name}</b></td>
                    </tr>
                `;
                    if (menu.children.length > 0) {
                        menu.children.forEach(child => {
                            let checkedChild = data.role.menus.some(rm => rm
                                .id === child.id) ? 'checked' : '';
                            menuHtml += `
                            <tr>
                                <td><input type="checkbox" name="menu_ids[]" value="${child.id}" ${checkedChild}></td>
                                <td class="pl-4">↳ <i class="${child.icon}"></i> ${child.name}</td>
                            </tr>
                        `;
                        });
                    }
                });

                // build permission table
                data.permissions.forEach(perm => {
                    let checked = data.role.permissions.some(rp => rp.id === perm.id) ?
                        'checked' : '';
                    permHtml += `
                    <tr>
                        <td><input type="checkbox" name="permission_ids[]" value="${perm.id}" ${checked}></td>
                        <td>${perm.name}</td>
                        <td>${perm.description ?? '-'}</td>
                    </tr>
                `;
                });

                $('#menu-table-body').html(menuHtml);
                $('#permission-table-body').html(permHtml);
            });
        });

        $('#roleAccessForm').on('submit', function(e) {
            e.preventDefault();
            let roleId = $('.select2').val();

            $.ajax({
                url: '/' + roleId + '/access',
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    // tampilkan loader
                    $('#proses-loader').css('display', 'flex');
                },
                success: function(res) {
                    // sembunyikan loader
                    $('#proses-loader').hide();

                    // tampilkan notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                error: function(xhr) {
                    $('#proses-loader').hide();

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data!',
                    });
                }
            });
        });
    });
</script>
