management_apk — Indopinetmart

management_apk adalah aplikasi apk_management yang dibangun menggunakan Laravel (backend) dan JavaScript (frontend & tooling).
Aplikasi ini dirancang untuk mempermudah manajemen APK dalam lingkungan yang scalable, aman, dan modern fullstack.

<p align="center"> <a href="https://laravel.com" target="_blank"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"> </a> </p> <p align="center"> <a href="https://github.com/indopinetmart/management_apk/actions"> <img src="https://github.com/indopinetmart/management_apk/workflows/Laravel%20CI/CD/badge.svg" alt="CI/CD Status"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/badge/laravel-10.x-red" alt="Laravel Version"> </a> <a href="https://nodejs.org/"> <img src="https://img.shields.io/badge/node-%3E%3D18-green" alt="Node.js Version"> </a> <a href="LICENSE"> <img src="https://img.shields.io/badge/license-MIT-blue" alt="License"> </a> </p>
📖 Tentang Project

Tujuan project:

Mengelola APK secara efisien

Menyediakan API dan interface yang mudah digunakan

Memanfaatkan kekuatan Laravel dalam routing, ORM, job, event

Memanfaatkan JavaScript modern (ES6, Vite, TailwindCSS, React/Vanilla) untuk frontend

Menjadi dasar aplikasi yang scalable & maintainable

✨ Fitur Utama

CRUD APK

Autentikasi pengguna

Logging aktivitas

Role-Based Access Control (RBAC)

Dukungan multi-user & multi-level role

CI/CD dengan zero downtime deployment

Build frontend real-time dengan Vite

🛠️ Teknologi yang Digunakan
Backend (Laravel)

Routing cepat & sederhana

Eloquent ORM yang powerful

Database migrations & seeder

Queue & job processing

Event broadcasting

Multi-auth guard & role management

Frontend & Tooling (JavaScript)

Node.js + NPM/Yarn (package management)

Vite (build tool super cepat)

TailwindCSS (utility-first styling)

React / Vanilla JS (UI interaktif)

ESLint & Prettier (konsistensi kode)

Jest / Vitest (testing JS)

📂 Struktur Folder (High Level)
management_apk/
├── app/             # Core Laravel (Models, Controllers, Middleware, dll.)
├── bootstrap/       # Bootstrap file Laravel
├── config/          # Konfigurasi aplikasi
├── database/        # Migration, seeder, factory
├── public/          # Public folder (index.php, assets, build)
├── resources/
│   ├── js/          # File JS (ES6/React/Vanilla)
│   ├── css/         # Tailwind/SCSS
│   └── views/       # Blade templates
├── routes/          # Web, API, console route
├── storage/         # Cache, logs, uploads
├── tests/           # Unit & feature tests
└── vite.config.js   # Config Vite

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
npm run dev    # development
npm run build  # production

🧪 Testing

Backend: PHPUnit (unit & feature test)

Frontend: Vitest / Jest

CI/CD: GitHub Actions otomatis setiap push ke main / develop

🔄 CI/CD (Zero Downtime)

Workflow GitHub Actions sudah mencakup:

CI → Build & test otomatis dengan PHP + MySQL container

CD → Deploy ke Hostinger via SSH dengan zero downtime

Backup otomatis storage & public sebelum release baru

Cleanup release lama & backup > 3 hari

📜 Lisensi

Project ini dirilis di bawah lisensi MIT.
