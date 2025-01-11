<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConverter
{

    private $apiKey;

    public static $baseUrl = 'https://free.currconv.com';

    public function __construct($apiKey)
    {

        $this->apiKey = $apiKey;
    }

    public function convert($from, $to)
    {

        $q = "{$from}_{$to}";

        $response = Http::baseUrl(CurrencyConverter::$baseUrl)
            ->get('/api/v7/convert', [
                'q' => $q,
                'compact' => 'y',
                'apiKey' => $this->apiKey
            ]);

        $result = $response->json();

        // dd($result);

        return $result[$q]['val'];
    }
}
