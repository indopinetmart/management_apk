# Management APK

Project **Management APK** dibangun menggunakan [Laravel](https://laravel.com) dan sudah dilengkapi dengan **CI/CD pipeline via GitHub Actions**.

## Fitur
- Laravel 10 dengan PHP 8.2
- Database MySQL
- CI untuk build & test otomatis di GitHub Actions
- CD (Continuous Deployment) via Docker & SSH
- Frontend menggunakan Vite + TailwindCSS

## Instalasi
```bash
# Clone repo
git clone https://github.com/indopinetmart/management_apk.git
cd management_apk

# Install dependency backend
composer install
cp .env.example .env
php artisan key:generate

# Install dependency frontend
npm install
npm run dev

# Jalankan migrasi
php artisan migrate
