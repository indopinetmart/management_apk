<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>IPM | Verifikasi Email</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon/logopt.png') }}">
    <style>
        /* ===== Body Fullscreen & Center ===== */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #6a0dad; /* ungu */
            font-family: Arial, sans-serif;
            color: #FFDE20FF;
            text-align: center;
        }

        /* Container */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 2rem;
        }

        #verify-status {
            font-size: 1.2rem;
            margin-top: 20px;
            white-space: pre-line; /* support \n */
        }

        /* ===== Loader Animation ===== */
        .loader {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid #FFDE20FF;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Verifikasi Email</h2>
        <div class="loader" id="loader"></div>
        <p id="verify-status">Sedang memverifikasi...</p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const params = new URLSearchParams(window.location.search);
            const token = params.get("token");
            const email = params.get("email");
            const resolution = params.get("resolution") || `${window.screen.width}x${window.screen.height}`;
            const latitude = params.get("lat");
            const longitude = params.get("long");

            const statusEl = document.getElementById("verify-status");
            const loaderEl = document.getElementById("loader");

            if (!token || !email) {
                statusEl.innerText = "❌ Link verifikasi tidak valid";
                loaderEl.style.display = "none";
                return;
            }

            try {
                // 1️⃣ Verifikasi email
                const resVerify = await fetch("{{ route('email.verify.api') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        token,
                        email,
                        resolution,
                        lat: latitude,
                        long: longitude
                    })
                });
                const verifyData = await resVerify.json();

                if (!verifyData.success) {
                    statusEl.innerText = "❌ " + verifyData.message;
                    loaderEl.style.display = "none";
                    return;
                }

                const jwt = verifyData.data.token;

                // 2️⃣ Panggil authorize/check
                const resAuth = await fetch("{{ route('authorize.check') }}", {
                    method: "GET",
                    credentials: "include",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": "Bearer " + jwt
                    }
                });
                const authData = await resAuth.json();

                if (authData.success) {
                    statusEl.innerText =
                        "✅ Email diverifikasi...\nSilahkan tutup halaman login yang pertama...\nHalaman ini akan langsung membawa Anda masuk ke App_IPM";
                    loaderEl.style.display = "none";
                    setTimeout(() => window.location.href = "/app_ipm", 2000);
                } else {
                    statusEl.innerText = "⚠️ " + authData.message;
                    loaderEl.style.display = "none";
                }
            } catch (e) {
                console.error("Error:", e);
                statusEl.innerText = "⚠️ Terjadi kesalahan saat verifikasi login";
                loaderEl.style.display = "none";
            }
        });
    </script>
</body>

</html>
