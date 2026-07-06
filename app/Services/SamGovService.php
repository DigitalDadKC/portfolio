<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class SamGovService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.samgov.key');
    }

    public function searchLodging(string $year)
    {
        $response = Http::get(
            "https://api.gsa.gov/travel/perdiem/v2/rates/conus/lodging/{$year}",
            [
                'api_key' => config('services.gsa.key'),
            ]
        );

        if ($response->failed()) {
            throw new Exception(
                'GSA API error: ' . $response->body()
            );
        }

        return $response->json();
    }

    public function getContractAwards() {
        $apiKey = config('services.samgov.key');

        $response = Http::get(
            "https://api.sam.gov/contract-awards/v1/search?api_key={$apiKey}&naicsCode=513310&limit=100"
        );

        if ($response->failed()) {
            throw new Exception(
                'SAM.gov API error: ' . $response->body()
            );
        }

        return $response->json();
    }
}
