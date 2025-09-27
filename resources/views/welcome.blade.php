<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Splash | Pinetmart Management</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon/logopt.png') }}">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

    <!-- Redirect Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const loginUrl = "{{ route('login.page') }}";
            setTimeout(() => {
                window.location.href = loginUrl;
            }, 2000); // redirect setelah 2.5 detik
        });
    </script>
</head>

<body>
    <div class="logo-container">
        <img class="logo" src="{{ asset('assets/images/icon/icon_pinetmart.png') }}" alt="Pinetmart Logo" />
        <div class="shadow"></div>
    </div>
    <h1 class="title">Welcome To Pinetmart Management</h1>


    <script>
        // === BLOKIR INSPECT ELEMENT ===
        if (window.innerWidth > 768) {
            document.addEventListener('keydown', function(event) {
                const key = event.key.toLowerCase();

                // Blokir F12 (keyCode 123, code 'F12')
                if (event.keyCode === 123 || event.code === "F12") {
                    event.preventDefault();
                    event.stopPropagation();
                    Swal.fire({
                        icon: "warning",
                        title: "Akses Ditolak",
                        text: "Fitur developer tools dinonaktifkan.",
                    });
                    return false;
                }

                // Blokir Ctrl+U
                if (event.ctrlKey && key === 'u') {
                    event.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Akses Ditolak",
                        text: "View Source dinonaktifkan.",
                    });
                }

                // Blokir Ctrl+Shift+I/J/C
                if (event.ctrlKey && event.shiftKey && ['i', 'j', 'c'].includes(key)) {
                    event.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Akses Ditolak",
                        text: "Fitur developer tools dinonaktifkan.",
                    });
                }
            });

            // Blokir klik kanan
            document.addEventListener('contextmenu', function(event) {
                event.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Akses Ditolak",
                    text: "Klik kanan dinonaktifkan.",
                });
            });
        }
    </script>
</body>

</html>
