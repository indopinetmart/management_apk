<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sesi Berakhir</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @include('partials.loader')

    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Sesi Berakhir',
            text: 'Sesi anda telah habis, silakan login kembali.',
            confirmButtonText: 'OK',
            allowOutsideClick: false, // biar ga auto-close klik luar
            allowEscapeKey: false, // biar ga auto-close ESC
        }).then((result) => {
            if (result.isConfirmed) {
                // 🔥 Tampilkan loader
                window.parent.document.getElementById('proses-loader').style.display = 'flex';

                // 🔴 Kirim form GET logout ke parent window (aplikasi utama)
                let form = document.createElement("form");
                form.method = "GET";
                form.action = "{{ route('logout.reset.page') }}";

                // tambahkan CSRF token
                let csrf = document.createElement("input");
                csrf.type = "hidden";
                csrf.name = "_token";
                csrf.value = "{{ csrf_token() }}";
                form.appendChild(csrf);

                // inject ke parent document lalu submit
                window.parent.document.body.appendChild(form);
                form.submit();
            }
        });
    </script>
</body>

</html>
