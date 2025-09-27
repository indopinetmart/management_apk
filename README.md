management_apk Indopinetmart

management_apk adalah aplikasi apk_management yang dibangun menggunakan Laravel (backend) dan JavaScript (frontend & tooling).
Aplikasi ini dirancang untuk mempermudah manajemen APK dalam lingkungan yang scalable, aman, dan modern fullstack.

<p align="center"> <a href="https://laravel.com" target="_blank"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"> </a> </p> <p align="center"> <a href="https://github.com/laravel/framework/actions"> <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"> </a> </p>
📖 Tentang Project

Project ini bertujuan untuk:

Mengelola APK secara efisien

Menyediakan API dan interface yang mudah digunakan

Memanfaatkan kekuatan Laravel dalam routing, ORM, dan background job

Memanfaatkan JavaScript modern (ES6, Vite, Tailwind, React/Vanilla) untuk frontend

Menjadi dasar pengembangan aplikasi yang scalable dan maintainable

✨ Fitur Utama

CRUD APK

Autentikasi pengguna

Logging aktivitas

Manajemen izin akses (Role-Based Access Control)

Dukungan multi-user dan multi-level role

CI/CD dengan zero downtime deployment

Build frontend real-time dengan Vite

🛠️ Teknologi yang Digunakan
Backend (Laravel)

Routing cepat dan sederhana

ORM Eloquent yang powerful

Database migrations & seeder

Queue & job processing

Event broadcasting

Multi-auth guard & role management

Frontend & Tooling (JavaScript)

Node.js + NPM/Yarn → package management

ESLint & Prettier → menjaga konsistensi kode

📂 Struktur Folder (High Level)
management_apk/
├── app/              # Core Laravel app (Models, Controllers, Middleware, dll.)
├── bootstrap/        # Bootstrap file untuk Laravel
├── config/           # Konfigurasi Laravel
├── database/         # Migration, seeder, dan factory
├── public/           # Public folder (index.php, assets, build)
├── resources/
│   ├── js/           # File JS (ES6/React/Vue)
│   ├── css/          # Style (Tailwind/SCSS)
│   └── views/        # Blade templates
├── routes/           # Web, API, console route
├── storage/          # Cache, logs, uploads
├── tests/            # Unit & feature tests
└── vite.config.js    # Config Vite untuk build frontend

🚀 Instalasi
1. Clone Repo
git clone https://github.com/indopinetmart/management_apk.git
cd management_apk

2. Install Dependency
composer install
npm install

3. Setup Environment
cp .env.example .env
php artisan key:generate

4. Migration & Build Asset
php artisan migrate --seed
npm run dev   # untuk development
npm run build # untuk production

🧪 Testing

PHPUnit untuk test backend

Jest / Vitest untuk test JavaScript frontend

CI/CD GitHub Actions → otomatis jalan setiap push ke main/develop

🔄 CI/CD (Zero Downtime)

Project ini sudah dilengkapi workflow GitHub Actions untuk:

CI: Build & test otomatis dengan PHP + MySQL container

CD: Deploy ke Hostinger via SSH dengan zero downtime

Backup otomatis + cleanup release lama

📜 Lisensi

Project ini dirilis di bawah lisensi MIT
.

👉 README ini sudah siap dipakai langsung untuk repo.

Mau saya bikinkan juga contoh badge tambahan (coverage, Node.js version, Laravel version, dsb.) biar lebih profesional?
