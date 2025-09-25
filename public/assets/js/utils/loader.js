window.showLoader = function (text = '') {
    const loader = document.getElementById('global-loader');
    const loaderText = document.getElementById('loader-text');
    if (loader) {
        loader.style.display = 'flex';
        if (loaderText) loaderText.textContent = text;
    }
};

window.hideLoader = function () {
    const loader = document.getElementById('global-loader');
    const loaderText = document.getElementById('loader-text');
    if (loader) {
        loader.style.display = 'none';
        if (loaderText) loaderText.textContent = '';
    }
};

// 👇 Cek saat app layout dimuat: jika ada skipLoader maka langsung sembunyikan loader
document.addEventListener('DOMContentLoaded', () => {
    const skip = localStorage.getItem('skipLoader');
    if (skip === 'true') {
        window.hideLoader();
        localStorage.removeItem('skipLoader'); // reset supaya hanya 1x saja
    }
});
