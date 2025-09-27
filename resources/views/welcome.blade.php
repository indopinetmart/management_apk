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
        // === BLOKIR INSPECT ELEMENT (ANTI SPAM) ===
        if (window.innerWidth > 768) {
            function blockEvent(event) {
                const key = (event.key || "").toLowerCase();

                // Blokir F12
                if (event.keyCode === 123 || event.code === "F12") {
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    Swal.fire({
                        icon: "warning",
                        title: "Akses Ditolak",
                        text: "Fitur developer tools dinonaktifkan.",
                    });
                    return false;
                }

                // Blokir Ctrl+U
                if (event.ctrlKey && key === "u") {
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    Swal.fire({
                        icon: "warning",
                        title: "Akses Ditolak",
                        text: "View Source dinonaktifkan.",
                    });
                    return false;
                }

                // Blokir Ctrl+Shift+I/J/C
                if (event.ctrlKey && event.shiftKey && ["i", "j", "c"].includes(key)) {
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    Swal.fire({
                        icon: "warning",
                        title: "Akses Ditolak",
                        text: "Fitur developer tools dinonaktifkan.",
                    });
                    return false;
                }
            }

            // Cegah di semua event keyboard
            document.addEventListener("keydown", blockEvent, true);
            document.addEventListener("keyup", blockEvent, true);
            document.addEventListener("keypress", blockEvent, true);

            // Blokir klik kanan
            document.addEventListener("contextmenu", function(event) {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                Swal.fire({
                    icon: "warning",
                    title: "Akses Ditolak",
                    text: "Klik kanan dinonaktifkan.",
                });
                return false;
            }, true);

            // Opsional: deteksi kalau DevTools kebuka (backup proteksi)
            setInterval(function() {
                if (window.outerWidth - window.innerWidth > 160 ||
                    window.outerHeight - window.innerHeight > 160) {
                    document.body.innerHTML = "";
                    alert("Developer tools terdeteksi! Akses ditutup.");
                    window.location.href = "/";
                }
            }, 1000);
        }
    </script>
</body>

</html>
