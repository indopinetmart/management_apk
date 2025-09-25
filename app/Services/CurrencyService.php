<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\CurrencyRate;
use Exception;

class CurrencyService
{
    /**
     * Ambil konversi normal (rate * amount)
     */
    public static function convert($from = 'IDR', $to = 'USD', $amount = 1)
    {
        try {
            $rateRecord = CurrencyRate::where('from_currency', $from)
                ->where('to_currency', $to)
                ->first();

            if ($rateRecord) {
                return $rateRecord->rate * $amount;
            }

            // Jika belum ada, request API
            $response = Http::get(config('currency.base_url'), [
                'apikey' => config('currency.api_key'),
                'base_currency' => $from,
                'currencies' => $to
            ]);

            if ($response->successful()) {
                $rate = $response->json()['data'][$to]['value'];

                CurrencyRate::updateOrCreate(
                    ['from_currency' => $from, 'to_currency' => $to],
                    [
                        'rate' => round($rate, 2),
                        'gcv' => 314159
                    ]
                );

                return $rate * $amount;
            }

            throw new Exception("API request failed: {$from} → {$to}");
        } catch (Exception $e) {
            // Return exception message supaya bisa ditangani frontend dengan SweetAlert
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Hitung 1 Pi = gcv * rate terbaru
     */
    public static function piValue($from = 'USD', $to = 'IDR')
    {
        $gcv = DB::table('currency_rates')->value('gcv'); // 314159
        $rate = DB::table('currency_rates')->where('from_currency', $from)->where('to_currency', $to)->value('rate');

        if (!$gcv || !$rate) {
            return ['error' => true, 'message' => 'Data kurs tidak ditemukan'];
        }

        $piInIdr = $gcv * $rate; // 314159 * 16362 = 5,140,583,717

        return [
            'gcv'   => $gcv,
            'rate'  => $rate,
            'idr'   => $piInIdr,
        ];
    }

    /**
     * Update rate dari API (digunakan di scheduler)
     */
    public static function updateRate($from = 'IDR', $to = 'USD')
    {
        try {
            $response = Http::get(config('currency.base_url'), [
                'apikey' => config('currency.api_key'),
                'base_currency' => $from,
                'currencies' => $to
            ]);

            if ($response->successful()) {
                $rate = $response->json()['data'][$to]['value'];

                // 🔥 Hapus record lama sebelum simpan baru
                CurrencyRate::where('from_currency', $from)
                    ->where('to_currency', $to)
                    ->delete();

                // Simpan data baru
                CurrencyRate::create([
                    'from_currency' => $from,
                    'to_currency'   => $to,
                    'rate'          => $rate,
                    'gcv'           => 314159
                ]);

                return ['success' => true, 'message' => "{$from} → {$to} updated"];
            } else {
                throw new Exception("API request failed: {$from} → {$to}");
            }
        } catch (Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
