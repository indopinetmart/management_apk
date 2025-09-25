<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: 'Maaf, anda tidak memiliki akses untuk ini.',
            confirmButtonText: 'Kembali'
        }).then(() => {
            // kalau halaman ini dibuka di dalam iframe
            if (window.self !== window.top) {
                // Tutup tab iframe AdminLTE
                if (parent.$ && parent.$('.nav-iframe-tab').length) {
                    let activeTab = parent.$('.nav-iframe-tab.active');
                    if (activeTab.length) {
                        activeTab.find('.btn-iframe-close').trigger('click');
                    }
                }
                // redirect parent ke dashboard
                parent.window.location.href = "{{ route('app.ipm') }}";
            } else {
                // kalau bukan iframe, langsung redirect
                window.location.href = "{{ route('app.ipm') }}";
            }
        });
    </script>
</body>

</html>
