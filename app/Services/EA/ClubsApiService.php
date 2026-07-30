<?php

declare(strict_types=1);

namespace App\Services\EA;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClubsApiService
{
    public const API_URL = 'https://proclubs.ea.com/api/fc/';

    public function performApiCall(string $endpoint, array $params = []): ?array
    {
        try {
            $url = self::API_URL.$endpoint;

            $response = Http::timeout(15)
                ->retry(1, 200)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0',
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'DNT' => '1',
                    'Connection' => 'keep-alive',
                    'Referer' => 'https://www.ea.com/',
                    'Origin' => 'ea.com',
                    'Sec-Fetch-Dest' => 'empty',
                    'Sec-Fetch-Mode' => 'cors',
                    'Sec-Fetch-Site' => 'cross-site',
                ])
                ->withOptions([
                    'decode_content' => true,
                ])
                ->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('EA API request failed', [
                'endpoint' => $endpoint,
                'params' => $params,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('EA API request failed with exception', [
                'endpoint' => $endpoint,
                'params' => $params,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
