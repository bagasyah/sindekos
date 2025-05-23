document.addEventListener('DOMContentLoaded', function () {
    const loadingOverlay = document.getElementById('loading-overlay');

    // Fungsi untuk menampilkan loading overlay
    function showLoadingOverlay() {
        loadingOverlay.style.display = 'flex';
    }

    // Fungsi untuk menyembunyikan loading overlay
    function hideLoadingOverlay() {
        setTimeout(() => {
            loadingOverlay.style.display = 'none';
        }, 3000); // Menunda penutupan overlay selama 3 detik
    }

    // Tampilkan loading overlay saat link diklik
    document.addEventListener('click', function (event) {
        var target = event.target;
        if (
            target.tagName === 'A' &&
            !target.getAttribute('href').startsWith('#') &&
            !target.getAttribute('href').startsWith('javascript:')
        ) {
            showLoadingOverlay();
        }
    });

    // Tampilkan loading overlay saat form disubmit
    document.addEventListener('submit', function () {
        showLoadingOverlay();
    });

    // Sembunyikan loading overlay saat halaman selesai dimuat
    window.addEventListener('load', hideLoadingOverlay);

    // Tangani kasus ketika pengguna menekan tombol kembali
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            hideLoadingOverlay();
        }
    });
});
