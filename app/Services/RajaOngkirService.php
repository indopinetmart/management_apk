<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $baseUrl;
    protected $apiKeyCost;
    protected $apiKeyDelivery;

    public function __construct()
    {
        $this->baseUrl = config('services.rajaongkir.base_url');
        $this->apiKeyCost = config('services.rajaongkir.api_key_cost');
        $this->apiKeyDelivery = config('services.rajaongkir.api_key_delivery');
    }

    // =========================
    // Shipping Cost
    // =========================
    public function getShippingCost($origin, $destination, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKeyCost,
        ])->post($this->baseUrl . '/cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ]);

        return $response->json();
    }

    // =========================
    // Shipping Delivery (estimasi waktu)
    // =========================
    public function getShippingDelivery($origin, $destination, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKeyDelivery,
        ])->post($this->baseUrl . '/cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ]);

        // Ambil estimasi delivery dari response
        $data = $response->json();
        $delivery = [];

        if (isset($data['rajaongkir']['results'][0]['costs'])) {
            foreach ($data['rajaongkir']['results'][0]['costs'] as $cost) {
                $service = $cost['service'];
                $etd = $cost['cost'][0]['etd'] ?? null;
                $delivery[$service] = $etd;
            }
        }

        return $delivery;
    }

    // =========================
    // Lokasi: Provinsi
    // =========================
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKeyCost,
        ])->get($this->baseUrl . '/province');

        return $response->json();
    }

    // =========================
    // Lokasi: Kota/Kabupaten
    // =========================
    public function getCities($province_id)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKeyCost,
        ])->get($this->baseUrl . "/city/{$province_id}");

        return $response->json();
    }

    // =========================
    // Lokasi: Kecamatan/Subdistrict (paket Pro)
    // =========================
    public function getSubdistricts($city_id)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKeyCost,
        ])->get($this->baseUrl . '/subdistrict', [
            'city' => $city_id
        ]);

        return $response->json();
    }
}
