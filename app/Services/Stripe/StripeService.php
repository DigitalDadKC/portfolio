<?php

namespace App\Services\Stripe;

use Illuminate\Support\Facades\Http;

class StripeService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->apiKey  = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    protected function request()
    {
        return Http::withToken($this->apiKey)
            ->acceptJson()
            ->withHeader('X-Api-Key', config('services.signwell.api_key'))
            ->baseUrl($this->baseUrl);
    }
}
