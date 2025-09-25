<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataWilayahService
{
    private $baseUrl;

    public function __construct()
    {
        // Base URL tanpa tambahan `/api` atau `.json`
        $this->baseUrl = config('services.datawilayah.base_url', 'https://api.datawilayah.com/api');
    }

    /**
     * Ambil daftar provinsi
     */
    public function getProvinces()
    {
        $url = "{$this->baseUrl}/provinsi.json";
        $response = Http::get($url);

        if (!$response->successful()) {
            Log::error("DataWilayah API error [Provinces]", [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];
        }

        $data = $response->json();
        // res adalah dalam bentuk:
        // { "status": "success", "total": xxx, "data": [ ... ] }

        return collect($data['data'] ?? [])->map(function ($item) {
            return [
                'id'   => $item['kode_wilayah'] ?? null,
                'name' => $item['nama_wilayah'] ?? null,
                'raw'  => $item,
            ];
        })->toArray();
    }

    /**
     * Ambil daftar kota/kabupaten berdasarkan kode provinsi
     */
    public function getCities($provKode)
    {
        $url = "{$this->baseUrl}/kabupaten_kota/{$provKode}.json";
        $response = Http::get($url);

        if (!$response->successful()) {
            Log::error("DataWilayah API error [Cities]", [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];
        }

        $data = $response->json();

        return collect($data['data'] ?? [])->map(function ($item) {
            return [
                'id'   => $item['kode_wilayah'] ?? null,
                'name' => $item['nama_wilayah'] ?? null,
                'raw'  => $item,
            ];
        })->toArray();
    }

    /**
     * Ambil daftar kecamatan berdasarkan kode kota/kabupaten
     */
    public function getDistricts($kotakabKode)
    {
        $url = "{$this->baseUrl}/kecamatan/{$kotakabKode}.json";
        $response = Http::get($url);

        if (!$response->successful()) {
            Log::error("DataWilayah API error [Districts]", [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];
        }

        $data = $response->json();

        return collect($data['data'] ?? [])->map(function ($item) {
            return [
                'id'   => $item['kode_wilayah'] ?? null,
                'name' => $item['nama_wilayah'] ?? null,
                'raw'  => $item,
            ];
        })->toArray();
    }

    /**
     * Ambil daftar desa/kelurahan berdasarkan kode kecamatan
     */
    public function getVillages($districtKode)
    {
        $url = "{$this->baseUrl}/desa_kelurahan/{$districtKode}.json";
        $response = Http::get($url);

        if (!$response->successful()) {
            Log::error("DataWilayah API error [Villages]", [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];
        }

        $data = $response->json();

        return collect($data['data'] ?? [])->map(function ($item) {
            return [
                'id'       => $item['kode_wilayah'] ?? null,
                'name'     => $item['nama_wilayah'] ?? null,
                'zip_code' => $item['kode_pos'] ?? null,
                'raw'      => $item,
            ];
        })->toArray();
    }
}
