<?php

namespace App\Services\SignWell;

use Illuminate\Support\Facades\Http;

class SignWellService
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

    public function create(array $payload)
    {
        // dd($payload);
        return $this->request()
            ->post("/documents", $payload)
            ->json();
    }
    public function delete(string $documentId)
    {
        return $this->request()
            ->delete("{$this->baseUrl}/documents/id/{$documentId}")
            ->json();
    }

    public function getCredentials() {
        return $this->request()->get("/me")->json();
    }
}
