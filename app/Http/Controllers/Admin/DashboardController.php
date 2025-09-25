<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use App\Services\CurrencyService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Halaman utama dashboard admin
    public function admin_dashboard_page()
    {
        // ===== Update rate dari API =====
        $rateResult = CurrencyService::updateRate('USD', 'IDR');

        if (isset($rateResult['error'])) {
            $usdErrorMessage = $rateResult['message']; // error ambil rate USD → IDR
            $piErrorMessage  = $rateResult['message']; // error hitung Pi
        } else {
            $usdErrorMessage = null;
            $piErrorMessage  = null;
        }

        // ===== Ambil rate USD → IDR =====
        $usdResult = CurrencyService::convert('USD', 'IDR', 1);
        if (isset($usdResult['error'])) {
            $usd = 0;
            $usdErrorMessage = $usdResult['message']; // override jika gagal convert
        } else {
            // USD → IDR cukup dibulatkan (integer)
            $usd = round($usdResult);
        }

        // ===== Ambil 1 Pi (USD & IDR) =====
        $piResult = CurrencyService::piValue('USD', 'IDR');
        if (isset($piResult['error'])) {
            $piUSD        = 0;
            $piInIDR      = 0;
            $piErrorMessage = $piResult['message'];
        } else {
            // Ambil data dari service
            $piUSD   = $piResult['gcv'];   // 1 Pi = $314159
            $piRate  = $piResult['rate'];  // 1 $ = Rp.16.363
            $piInIDR = $piResult['idr'];   // 1 Pi = Rp.5,140,583,717

            // Format ke string untuk ditampilkan di view
            $piUSD   = '$' . number_format($piUSD, 0, ',', '.');
            $piRate  = 'Rp ' . number_format($piRate, 0, ',', '.');
            $piInIDR = 'Rp ' . number_format($piInIDR, 0, ',', '.');
        }

        // ===== Ambil record currency_rates dari DB =====
        $pi = CurrencyRate::where('from_currency', 'USD')
            ->where('to_currency', 'IDR')
            ->first();

        // Kirim ke view
        return view('admin.dashboard.index', compact(
            'usd',
            'piUSD',
            'piRate',
            'piInIDR',
            'pi',
            'usdErrorMessage',
            'piErrorMessage'
        ));
    }
}
