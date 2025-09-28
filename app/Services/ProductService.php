<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ProductService
{
    public string $baseUrl;
    public string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.product.base_url') ?? '';
        $this->apiKey  = config('services.product.api_key') ?? '';

        if (empty($this->baseUrl) || empty($this->apiKey)) {
            throw new \Exception('ProductService config not set.');
        }
    }

    public function ping(): array
    {
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->get($this->baseUrl . '/api/ping');

        return $response->json();
    }
}
