<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Management | IPM')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon/logopt.png') }}">

    <!-- Google Font & Font Awesome -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('tamplate/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Lokasi -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('tamplate/dist/css/adminlte.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('tamplate/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('tamplate/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('tamplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('tamplate/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('tamplate/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('tamplate/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">



    <style>
        #logout-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            /* transparan + blur */
            backdrop-filter: blur(4px);
            /* efek blur */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* pastikan di atas */
        }

        #logout-loader .loader-content {
            text-align: center;
        }

        #logout-loader img {
            width: 80px;
            height: 80px;
        }

        #logout-loader p {
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }
    </style>

    <!-- ================= STYLE ================= -->
    <style>
        /* Container seukuran KTP */
        .ktp-frame {
            position: relative;
            display: inline-block;
        }

        #videoKTP,
        #canvasKTP,
        #ktpFrameOverlay {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        #canvasKTP {
            display: none;
            /* default hidden */
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            /* di atas video */
        }

        #ktpFrameOverlay {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 3;
            border: 3px dashed gold;
            /* contoh frame */
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        /* Modal responsif */
        .custom-modal {
            max-width: 95vw;
            margin: auto;
        }

        @media (min-width: 768px) {
            .custom-modal {
                max-width: 600px;
            }
        }

        #map {
            min-height: 300px;
            /* fallback kalau modal kecil */
            flex: 1;
        }


        .map-container {
            width: 100%;
            height: 50vh;
            /* tinggi 50% dari layar */
            max-height: 400px;
            /* biar tidak terlalu besar di desktop */
            border: 2px solid #6a0dad;
            border-radius: 8px;
        }

        /* Responsif tambahan */
        @media (max-width: 576px) {
            .map-container {
                height: 40vh;
                /* di HP jadi lebih kecil */
            }
        }

        .selfie-frame {
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        .selfie-frame video,
        .selfie-frame canvas {
            width: 100%;
            border-radius: 8px;
            border: 2px solid #6a0dad;
        }

        .modal-backdrop {
            background-color: rgba(255, 255, 255, 0) !important;
            /* benar-benar transparan */
            backdrop-filter: blur(6px);
            /* efek blur tetap jalan */
            -webkit-backdrop-filter: blur(6px);
            /* support Safari */
        }

        .loader-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #videoVerifikasi {
            filter: brightness(1.2);
        }

        #countdownOverlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 70px;
            font-weight: bold;
            color: white;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
            z-index: 9999;
        }

        /* Default merah untuk required yang kosong */
        .is-invalid {
            border: 1px solid red !important;
        }

        /* Hijau saat valid */
        .is-valid {
            border: 1px solid green !important;
        }

        #current-date {
            margin-left: auto;
            /* dorong ke ujung kanan */
        }

        .card-header {
            padding-left: 10px !important;
            /* kurangi padding kiri */
            padding-right: 10px !important;
            /* kurangi padding kanan */
        }
    </style>



</head>
