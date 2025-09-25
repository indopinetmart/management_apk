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
</body>
</html>

