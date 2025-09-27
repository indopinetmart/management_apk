# 📦 management_apk — Indopinetmart

`management_apk` adalah aplikasi **apk_management** yang dibangun dengan **Laravel (backend)** dan **JavaScript modern (frontend & tooling)**.  
Aplikasi ini dirancang untuk mendukung kebutuhan **enterprise-level** dalam manajemen APK yang **aman, efisien, dan scalable**.

---

## 📊 Status Project

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="250" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/indopinetmart/management_apk/actions">
    <img src="https://img.shields.io/github/actions/workflow/status/indopinetmart/management_apk/ci.yml?branch=main&label=CI%2FCD" alt="CI/CD Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/badge/Laravel-11.x-red?logo=laravel" alt="Laravel Version">
  </a>
  <a href="https://nodejs.org/">
    <img src="https://img.shields.io/badge/Node.js-20.x-green?logo=node.js" alt="Node.js Version">
  </a>
  <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript">
    <img src="https://img.shields.io/badge/JavaScript-ES6-yellow?logo=javascript" alt="JavaScript Version">
  </a>
  <a href="https://react.dev/">
    <img src="https://img.shields.io/badge/React-18.x-61DAFB?logo=react" alt="React">
  </a>
  <a href="https://tailwindcss.com/">
    <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?logo=tailwind-css" alt="TailwindCSS">
  </a>
  <a href="https://vitejs.dev/">
    <img src="https://img.shields.io/badge/Vite-5.x-646CFF?logo=vite" alt="Vite">
  </a>
  <a href="LICENSE">
    <img src="https://img.shields.io/github/license/indopinetmart/management_apk" alt="License">
  </a>
</p>

---

## 📖 Ringkasan Proyek

Aplikasi ini dibangun untuk:

- Mengelola APK dengan **standar enterprise**  
- Menyediakan **API dan antarmuka pengguna** yang intuitif  
- Mengintegrasikan **Laravel** sebagai backend yang kuat (Routing, ORM, Job Queue, Event)  
- Mengoptimalkan frontend dengan **JavaScript modern (ES6, Vite, TailwindCSS, React/Vanilla)**  
- Memberikan fondasi aplikasi yang **scalable, maintainable, dan aman**  

---

## ✨ Fitur Utama

- ✅ **CRUD APK**  
- ✅ **Autentikasi & manajemen pengguna**  
- ✅ **Logging aktivitas pengguna & sistem**  
- ✅ **Role-Based Access Control (RBAC)**  
- ✅ **Dukungan multi-user & multi-level role**  
- ✅ **CI/CD pipeline dengan zero downtime deployment**  
- ✅ **Frontend build real-time dengan Vite**  

---

## 🛠️ Teknologi

### Backend (Laravel)
- Routing cepat & efisien  
- **Eloquent ORM** untuk manajemen data  
- Migration, seeder, & factory  
- Queue & job processing  
- Event broadcasting  
- Multi-auth guard & role management  

### Frontend & Tooling (JavaScript)
- **Node.js + NPM/Yarn** → package management  
- **Vite** → build tool super cepat  
- **TailwindCSS** → styling berbasis utility  
- **React / Vanilla JS** → UI interaktif  
- **ESLint & Prettier** → standar & konsistensi kode  
- **Jest / Vitest** → unit & integration testing  

---

## 📂 Struktur Direktori

```text
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
└── vite.config.js   # Konfigurasi Vite


🧪 Testing & Quality Assurance

- Backend → PHPUnit (unit & feature test)
- Frontend → Vitest / Jest
- Pipeline CI/CD → GitHub Actions otomatis di setiap push ke main atau develop


🔄 CI/CD (Zero Downtime Deployment)

- Pipeline GitHub Actions sudah mencakup:
- CI → Build & test otomatis dengan container (PHP + MySQL)
- CD → Deploy otomatis ke server (Hostinger) via SSH dengan strategi zero downtime
- Backup otomatis untuk direktori storage/ & public/ sebelum setiap release baru
- Cleanup otomatis untuk release lama & backup lebih dari 3 hari

📜 Lisensi

- Proyek ini dirilis di bawah lisensi MIT.
- Hak cipta © Indopinetmart — seluruh kontribusi terbuka untuk komunitas.
