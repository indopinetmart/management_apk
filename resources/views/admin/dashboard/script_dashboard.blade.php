<script>
    // ===== SweetAlert error dari controller =====
    @if(isset($usdErrorMessage) && $usdErrorMessage)
        Swal.fire('Gagal ambil rate USD → IDR', '{{ $usdErrorMessage }}', 'error');
    @endif

    @if(isset($piErrorMessage) && $piErrorMessage)
        Swal.fire('Gagal hitung nilai Pi', '{{ $piErrorMessage }}', 'error');
    @endif

    // ===== Update tanggal otomatis =====
    function updateDate() {
        const dateElem = document.getElementById('current-date');
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        dateElem.textContent = now.toLocaleDateString('id-ID', options);
    }

    updateDate(); // update saat page load
    setInterval(updateDate, 3600000); // update tiap 1 jam

</script>
