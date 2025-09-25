<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen form, input, dan preview
        const inputPhoto = document.getElementById('photo');
        const preview = document.getElementById('previewPhoto');
        const form = document.getElementById('formEditPhoto');

        // =========================
        // Preview foto sebelum upload
        // =========================
        inputPhoto.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; // Set preview image
                    preview.classList.remove('d-none'); // Tampilkan preview
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                // Reset preview jika batal pilih file
                preview.src = "#";
                preview.classList.add('d-none');
            }
        });

        // =========================
        // Submit form via AJAX
        // =========================
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            // Tentukan ukuran Swal berdasarkan lebar layar (responsive)
            const swalWidth = window.innerWidth <= 480 ? 320 : undefined; // ≤480px → handphone

            // Tampilkan loading SweetAlert
            Swal.fire({
                title: 'Memproses data...',
                text: 'Silakan tunggu sebentar',
                allowOutsideClick: false,
                width: swalWidth,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('user.updatePhoto') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    Swal.close(); // Tutup loading

                    if (data.success) {
                        // Update foto profil di halaman utama
                        const profileImg = document.querySelector("img[alt='Profile Photo']");
                        if (profileImg) {
                            profileImg.src = data.photo_url; // Set foto baru
                        }

                        // Tutup modal
                        $('#editPhotoModal').modal('hide');

                        // Reset form & preview
                        form.reset();
                        preview.src = "#";
                        preview.classList.add('d-none');

                        // Tampilkan alert sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Foto profil berhasil diperbarui.',
                            width: swalWidth
                        });
                    } else {
                        // Alert error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan.',
                            width: swalWidth
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.close(); // Tutup loading
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Gagal mengunggah foto.',
                        width: swalWidth
                    });
                });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // =========================
        // Ambil koordinat user dari controller
        // Jika tidak ada, gunakan default Malibu, CA
        // =========================
        var latitude = {{ $latitude ?? -6.522846 }}; // contoh default jika kosong
        var longitude = {{ $longitude ?? 106.827031 }}; // contoh default jika kosong

        // =========================
        // Inisialisasi map Leaflet
        // =========================
        var map = L.map('user-map').setView([latitude, longitude], 13);

        // =========================
        // Tambahkan layer OpenStreetMap
        // =========================
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        // =========================
        // Tambahkan marker (tidak bisa diedit)
        // =========================
        L.marker([latitude, longitude], {
                draggable: false
            })
            .addTo(map)
            .bindPopup("{{ $userData['alamat_rumah'] ?? 'Lokasi tidak tersedia' }}")
            .openPopup();

        // =========================
        // Catatan:
        // - Marker tetap statis, user tidak bisa menggeser
        // - Popup menampilkan alamat rumah dari database
        // - Koordinat fallback digunakan jika lokasi kosong
        // =========================
    });
</script>
