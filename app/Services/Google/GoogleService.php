<?php

namespace App\Services\Google;

use App\Models\User;
use Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class GoogleService
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

    public function createDocument(array $payload)
    {
        return $this->request()
            ->post("/documents", $payload)
            ->json();
    }

    public function getDocument(string $documentId)
    {
        return $this->request()
            ->get("{$this->baseUrl}/documents/{$documentId}")
            ->json();
    }

    public function getCredentials() {
        return $this->request()->get("/me")->json();
    }
}
