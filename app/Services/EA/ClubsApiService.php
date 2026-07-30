<?php

declare(strict_types=1);

namespace App\Services\EA;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClubsApiService
{
    public const API_URL = 'https://proclubs.ea.com/api/fc/';

    public function performApiCall(string $endpoint, array $params = []): ?array
    {
        $response = $this->doEnhancedApiCall($endpoint, $params);

        if (! $response) {
            return null;
        }

        try {
            $decoded = json_decode($response, associative: true);

            return is_array($decoded) ? $decoded : null;
        } catch (Exception $e) {
            Log::error('Failed to decode API response', ['error' => $e->getMessage()]);

            return null;
        }
    }

    private function doEnhancedApiCall(string $endpoint, array $params = []): ?string
    {
        $url = self::API_URL.$endpoint;

        $strategies = [
            [
                'name' => 'Community Working Headers (Firefox)',
                'headers' => [
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
                ],
            ],
            [
                'name' => 'Alternative Working Headers (Chrome)',
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection' => 'keep-alive',
                    'Referer' => 'https://www.ea.com/',
                    'Origin' => 'ea.com',
                ],
            ],
            [
                'name' => 'Fallback Headers',
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'DNT' => '1',
                    'Connection' => 'keep-alive',
                    'Referer' => 'https://www.ea.com/',
                    'Origin' => 'https://www.ea.com',
                    'Sec-Fetch-Dest' => 'empty',
                    'Sec-Fetch-Mode' => 'cors',
                    'Sec-Fetch-Site' => 'cross-site',
                ],
            ],
        ];

        foreach ($strategies as $strategy) {
            Log::info('Trying API strategy: '.$strategy['name'], ['url' => $url]);

            try {
                $response = Http::timeout(15)
                    ->connectTimeout(15)
                    ->withHeaders($strategy['headers'])
                    ->withOptions([
                        'verify' => false,
                        'allow_redirects' => true,
                    ])
                    ->get($url, $params);

                $statusCode = $response->status();

                Log::info('Strategy result', [
                    'strategy' => $strategy['name'],
                    'http_code' => $statusCode,
                    'response_length' => strlen($response->body()),
                ]);

                if ($response->successful() && ! str_contains($response->body(), 'Access Denied')) {
                    Log::info('Strategy succeeded: '.$strategy['name']);

                    return $response->body();
                }
            } catch (Exception $e) {
                Log::error('API request failed with exception', [
                    'strategy' => $strategy['name'],
                    'message' => $e->getMessage(),
                ]);
            }
        }

        Log::error('All API strategies failed', ['endpoint' => $endpoint]);

        return null;
    }
}
