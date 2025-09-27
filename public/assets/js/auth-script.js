// ============================================================
// 📌 AUTH-SCRIPT.JS – Versi Final (Device + IP Geolocation + Lockout)
// ============================================================

document.addEventListener("DOMContentLoaded", function () {
    // ============================================
    // 🔗 ELEMENT REFERENCES
    // ============================================
    const form = $("#loginForm");
    const emailInput = $("#email");
    const passwordInput = $("#password");
    const togglePasswordBtn = $("#togglePassword");
    const rememberCheckbox = $("#remember");

    let userLatitude = null;
    let userLongitude = null;
    let ipLocation = null;

    // ============================================
    // 📱 DEVICE & BROWSER INFO
    // ============================================
    function generateDeviceId() {
        let deviceId = localStorage.getItem("device_id");
        if (!deviceId) {
            deviceId = crypto.randomUUID
                ? crypto.randomUUID()
                : `${Date.now()}-${Math.floor(Math.random() * 10000)}`;
            localStorage.setItem("device_id", deviceId);
        }
        return deviceId;
    }

    function detectBrowser(ua) {
        ua = ua || navigator.userAgent;
        if (/Chrome/.test(ua) && !/Edge/.test(ua)) return "Chrome";
        if (/Firefox/.test(ua)) return "Firefox";
        if (/Safari/.test(ua) && !/Chrome/.test(ua)) return "Safari";
        if (/Edge/.test(ua)) return "Edge";
        if (/OPR|Opera/.test(ua)) return "Opera";
        return "Unknown";
    }

    const fullUserAgent = navigator.userAgent || "Unknown";
    const browserName = detectBrowser(fullUserAgent);
    const deviceId = generateDeviceId();
    const platform = navigator.platform || "Unknown";
    const resolution = `${window.screen.width}x${window.screen.height}`;

    console.log("📌 Device Info Debug:");
    console.log("Device ID     :", deviceId);
    console.log("Platform      :", platform);
    console.log("Resolution    :", resolution);
    console.log("Full User-Agent:", fullUserAgent);
    console.log("Browser (UI)  :", browserName);

    // ============================================
    // 🌎 IP-BASED GEOLOCATION FETCH
    // ============================================
    async function fetchIpGeolocation() {
        try {
            // Menggunakan layanan ipapi untuk ambil lokasi berdasarkan IP
            const response = await fetch("https://ipapi.co/json/");
            ipLocation = await response.json();

            // Isi variabel latitude & longitude
            userLatitude = ipLocation.latitude;
            userLongitude = ipLocation.longitude;

            // Debug log lokasi berdasarkan IP
            console.log("📍 Lokasi dari IP:");
            console.log("IP        :", ipLocation.ip);
            console.log("Kota      :", ipLocation.city);
            console.log("Region    :", ipLocation.region);
            console.log("Negara    :", ipLocation.country_name);
            console.log("Organisasi:", ipLocation.org);
            console.log("Kode Pos  :", ipLocation.postal);
            console.log("Timezone  :", ipLocation.timezone);
            console.log("Latitude  :", ipLocation.latitude);
            console.log("Longitude :", ipLocation.longitude);
        } catch (error) {
            console.warn("⚠️ Gagal mengambil lokasi IP:", error);
            Swal.fire({
                icon: "warning",
                title: "Gagal Mengambil Lokasi",
                text: "Tidak dapat mendeteksi lokasi berdasarkan IP.",
            });
        }
    }

    // ============================================
    // ✅ Loader
    // ============================================
    function showLoader(text = "Sedang memuat...") {
        $("#loader-text").text(text);
        $("#global-loader").css("display", "flex");
    }
    function hideLoader() {
        $("#global-loader").css("display", "none");
    }

    // ============================================
    // 👁 Toggle Password Visibility
    // ============================================
    function initPasswordToggle() {
        if (!togglePasswordBtn.length || !passwordInput.length) return;
        togglePasswordBtn.on("click", function () {
            const isPassword = passwordInput.attr("type") === "password";
            passwordInput
                .css("transition", "opacity 0.2s ease")
                .css("opacity", "0");
            setTimeout(() => {
                passwordInput.attr("type", isPassword ? "text" : "password");
                togglePasswordBtn.toggleClass("fa-eye", !isPassword);
                togglePasswordBtn.toggleClass("fa-eye-slash", isPassword);
                passwordInput.css("opacity", "1");
            }, 200);
        });
    }

    // ============================================
    // ⏳ Lockout Countdown (Swal)
    // ============================================
    function showLockoutCountdown(lockoutData) {
        if (!lockoutData || !lockoutData.lockout_seconds) return;

        let remaining = lockoutData.lockout_seconds;
        const message = lockoutData.message || "Akun dibatasi sementara.";

        Swal.fire({
            icon: "warning",
            title: "Akses Dibatasi",
            html: `${message}<br><b>Coba lagi dalam <span id="lockout-timer">${formatTime(
                remaining
            )}</span></b>`,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                const timerEl = document.getElementById("lockout-timer");
                const interval = setInterval(() => {
                    remaining--;
                    if (timerEl) timerEl.textContent = formatTime(remaining);
                    if (remaining <= 0) {
                        clearInterval(interval);
                        Swal.close();
                        location.reload();
                    }
                }, 1000);
            },
        });
    }

    function formatTime(totalSeconds) {
        const min = Math.floor(totalSeconds / 60);
        const sec = totalSeconds % 60;
        return `${min}:${sec < 10 ? "0" + sec : sec} menit`;
    }

    // ============================================
    // 🟡 Handle Login (AJAX jQuery)
    // ============================================
    function handleLogin(e) {
        e.preventDefault();
        showLoader("Memeriksa kredensial...");

        const email = emailInput.val();
        const password = passwordInput.val();
        const remember = rememberCheckbox.is(":checked") ? 1 : 0;
        const csrfToken = form.find("input[name=_token]").val();

        if (!ipLocation) {
            hideLoader();
            Swal.fire({
                icon: "warning",
                title: "Lokasi Tidak Ditemukan",
                text: "Aktifkan GPS atau izinkan lokasi untuk melanjutkan login.",
            });
            return;
        }

        const loginData = {
            _token: csrfToken,
            email,
            password,
            remember,
            device_id: deviceId,
            user_agent: fullUserAgent,
            platform,
            resolution,
            browserName,
            latitude: userLatitude,
            longitude: userLongitude,

            // Data IP
            ip: ipLocation.ip,
            city: ipLocation.city,
            region: ipLocation.region,
            country: ipLocation.country_name,
            org: ipLocation.org,
            postal: ipLocation.postal,
            timezone: ipLocation.timezone,
        };

        // 🔹 Console log data login
        console.log("📤 Data login yang akan dikirim ke server:", loginData);

        form.find("button[type='submit']").prop("disabled", true);

        $.ajax({
            url: "/auth/login_post",
            method: "POST",
            data: loginData,
            dataType: "json",
            complete: function (xhr) {
                hideLoader();
                form.find("button[type='submit']").prop("disabled", false);
                console.log(
                    "📡 Response server:",
                    xhr.status,
                    xhr.responseJSON
                );

                if (xhr.status === 200) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: xhr.responseJSON?.message || "Login berhasil.",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                    });
                    setTimeout(() => {
                        if (xhr.responseJSON?.redirect)
                            window.location.href = xhr.responseJSON.redirect;
                    }, 2500);
                } else if (xhr.status === 202) {
                    Swal.fire({
                        icon: "info",
                        title: "Verifikasi Email Diperlukan",
                        html: `${
                            xhr.responseJSON?.message ||
                            "Silakan verifikasi email Anda sebelum melanjutkan."
                        }
                    <br><br><b>Email tujuan:</b> ${
                        xhr.responseJSON?.user?.email || "-"
                    }`,
                        confirmButtonText: "OK, Saya Mengerti",
                    }).then(() => {
                        setTimeout(function () {
                            window.open("", "_self");
                            window.close();
                        }, 2000);
                    });
                } else if (xhr.status >= 400) {
                    handleLoginError(xhr);
                }
            },
            error: function (xhr) {
                hideLoader();
                form.find("button[type='submit']").prop("disabled", false);
                handleLoginError(xhr);
            },
        });
    }

    // ============================================
    // 🔹 Error Handler
    // ============================================
    function handleLoginError(xhr) {
        console.error("❌ Error login:", xhr.status, xhr.responseJSON);

        if (xhr.status === 429) {
            showLockoutCountdown({
                message: xhr.responseJSON?.message,
                lockout_seconds: xhr.responseJSON?.lockout_seconds || 0,
            });
        } else if (xhr.status === 401) {
            Swal.fire({
                icon: "error",
                title: "Tidak Valid",
                text: xhr.responseJSON?.message || "Email atau password salah.",
            });
        } else if (xhr.status === 403) {
            Swal.fire({
                icon: "warning",
                title: "Akses Dibatasi",
                text:
                    xhr.responseJSON?.message ||
                    "Anda sudah login di device lain.",
            });
        } else if (xhr.status === 422) {
            Swal.fire({
                icon: "error",
                title: "Validasi Gagal",
                html: Object.values(xhr.responseJSON.errors).join("<br>"),
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Ups, Terjadi Kesalahan",
                text:
                    xhr.responseJSON?.message ||
                    "Terjadi kesalahan pada server.",
            });
        }
    }

    // ============================================
    // 🚀 INIT
    // ============================================
    initPasswordToggle();
    fetchIpGeolocation();
    form.on("submit", handleLogin);

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
            if (
                event.ctrlKey &&
                event.shiftKey &&
                ["i", "j", "c"].includes(key)
            ) {
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
        document.addEventListener(
            "contextmenu",
            function (event) {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                Swal.fire({
                    icon: "warning",
                    title: "Akses Ditolak",
                    text: "Klik kanan dinonaktifkan.",
                });
                return false;
            },
            true
        );

        // Opsional: deteksi kalau DevTools kebuka (backup proteksi)
        setInterval(function () {
            if (
                window.outerWidth - window.innerWidth > 160 ||
                window.outerHeight - window.innerHeight > 160
            ) {
                document.body.innerHTML = "";
                alert("Developer tools terdeteksi! Akses ditutup.");
                window.location.href = "/";
            }
        }, 1000);
    }
});
