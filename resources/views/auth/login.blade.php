<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Pinetmart Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ CSS Auth (Splash/Login Styling) -->
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

    <!-- ✅ Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon/logopt.png') }}">
</head>

<body>
    <!-- ✅ Global Loader -->
    <div id="global-loader"
        style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(255,255,255,0.8); z-index: 9999;">
        <div style="text-align: center;">
            <img id="loader-image" src="{{ asset('assets/images/loader/spiner_gif.gif') }}" alt="Loading..."
                style="width: 80px; height: 80px;">
            <p id="loader-text" style="margin-top: 10px; font-weight: bold;">Sedang memuat...</p>
        </div>
    </div>


    <!-- ✅ FORM LOGIN -->
    <form id="loginForm" class="login-box">
        @csrf

        <h3 class="shiny-title">Please Login</h3>

        <!-- ✅ Input Email -->
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required autocomplete="username">
        </div>

        <!-- ✅ Input Password -->
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" autocomplete="current-password"
                required>
            <i class="fas fa-eye toggle-password" id="togglePassword" style="cursor:pointer;"></i>
        </div>

        <!-- ✅ Checkbox Remember Me -->
        <div style="margin-top: 10px; margin-bottom: 15px; display: flex; align-items: center;">
            <input type="checkbox" name="remember" id="remember" style="margin-right: 8px;">
            <label for="remember" style="font-size: 14px; color:#ffe100;">Remember Me</label>
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">


        <!-- ✅ Tombol Login -->
        <button type="submit" class="gold-button mb-2">Login</button>

        <!-- ✅ Countdown -->
        <div id="countdown" class="fw-bold mt-5" style="text-align: center; color: gold;"></div>


        <!-- ✅ Versi Info -->
        <div style="margin-top: 20px; text-align: center; font-size: 12px; color: #ffe100;">
            <p>Pinetmart Management V_0.0.1</p>
        </div>
    </form>

    <!-- ✅ SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- ✅ jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ Script Login -->
    <script src="{{ asset('assets/js/auth-script.js') }}"></script>
</body>

</html>
