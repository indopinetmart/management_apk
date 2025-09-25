<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Logout & Reset Session</title>
    <style>
        /* ===== Body Fullscreen & Center ===== */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #6a0dad;
            /* ungu */
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
            white-space: pre-line;
            /* support \n */
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="loader"></div>
        <h2>Logout & Reset Session</h2>
        <div id="verify-status">{{ $message }}</div>
    </div>

    <script>
        setTimeout(function() {
            try {
                window.open('', '_self'); // hack agar bisa di-close
                window.close();
            } catch (e) {
                // kalau error langsung redirect
                window.location.href = '/';
            }

            // kalau 200ms setelah coba close masih terbuka, paksa redirect
            setTimeout(function() {
                if (!window.closed) {
                    window.location.href = '/';
                }
            }, 200);
        }, 3000); // 3 detik
    </script>

</body>

</html>
