// ============================================
    // ✅ Loader Handler
    // ============================================
    function showLoader(text = "Sedang memuat...") {
        document.getElementById("loader-text").innerText = text;
        document.getElementById("logout-loader").style.display = "flex";
    }
    function hideLoader() {
        document.getElementById("logout-loader").style.display = "none";
    }

    // ============================================
    // ✅ Logout dengan Loader Only
    // ============================================
    document.addEventListener("DOMContentLoaded", function () {
        const logoutBtn = document.getElementById("logoutBtn");
        if (!logoutBtn) return;

        logoutBtn.addEventListener("click", function (e) {
            e.preventDefault();

            Swal.fire({
                title: "Yakin mau logout?",
                text: "Sesi Anda akan diakhiri.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Logout",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    // 🔹 Tampilkan loader
                    showLoader("Sedang logout...");

                    fetch("/auth/logout", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                            Accept: "application/json",
                        },
                        body: JSON.stringify({}),
                    })
                        .then((response) => {
                            if (!response.ok) throw new Error("Gagal logout!");
                            return response.json();
                        })
                        .then(() => {
                            // 🔹 Redirect ke login page
                            window.location.href = "/auth/login_page";
                        })
                        .catch((error) => {
                            hideLoader();
                            Swal.fire("Error", error.message, "error");
                        });
                }
            });
        });
    });
