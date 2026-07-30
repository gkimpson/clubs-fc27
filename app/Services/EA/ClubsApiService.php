<?php

declare(strict_types=1);

namespace App\Services\EA;

use Exception;
use Illuminate\Support\Facades\Log;

class ClubsApiService
{
    public const API_URL = 'https://proclubs.ea.com/api/fc/';

    public function performApiCall(string $endpoint, array $params = []): ?array
    {
        $response = $this->doEnhancedApiCall($endpoint, $params);

        if (! $response || $response === '') {
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

    private function doEnhancedApiCall(string $endpoint, array $params = []): string
    {
        $url = self::API_URL.$endpoint.'?'.http_build_query($params);

        $strategies = [
            [
                'name' => 'Community Working Headers (Firefox)',
                'headers' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0',
                    'Accept: application/json, text/plain, */*',
                    'Accept-Language: en-US,en;q=0.9',
                    'Accept-Encoding: gzip, deflate, br',
                    'DNT: 1',
                    'Connection: keep-alive',
                    'Referer: https://www.ea.com/',
                    'Origin: ea.com',
                    'Sec-Fetch-Dest: empty',
                    'Sec-Fetch-Mode: cors',
                    'Sec-Fetch-Site: cross-site',
                ],
            ],
            [
                'name' => 'Alternative Working Headers (Chrome)',
                'headers' => [
                    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',
                    'Accept: application/json, text/plain, */*',
                    'Accept-Language: en-US,en;q=0.9',
                    'Accept-Encoding: gzip, deflate, br',
                    'Connection: keep-alive',
                    'Referer: https://www.ea.com/',
                    'Origin: ea.com',
                ],
            ],
            [
                'name' => 'Fallback Headers',
                'headers' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
                    'Accept: application/json, text/plain, */*',
                    'Accept-Language: en-US,en;q=0.9',
                    'Accept-Encoding: gzip, deflate, br',
                    'DNT: 1',
                    'Connection: keep-alive',
                    'Referer: https://www.ea.com/',
                    'Origin: https://www.ea.com',
                    'Sec-Fetch-Dest: empty',
                    'Sec-Fetch-Mode: cors',
                    'Sec-Fetch-Site: cross-site',
                ],
            ],
        ];

        foreach ($strategies as $strategy) {
            Log::info('Trying API strategy: '.$strategy['name'], ['url' => $url]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => $strategy['headers'],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            $curlErrno = curl_errno($curl);

            Log::info('Strategy result', [
                'strategy' => $strategy['name'],
                'http_code' => $httpCode,
                'curl_errno' => $curlErrno,
                'response_length' => is_string($response) ? strlen($response) : 0,
            ]);

            curl_close($curl);

            if ($httpCode === 200 && is_string($response) && ! str_contains($response, 'Access Denied') && ! empty($response)) {
                Log::info('Strategy succeeded: '.$strategy['name']);

                return $response;
            }

            if ($curlErrno !== 0) {
                $this->handleCurlError($curlErrno, $curlError, $httpCode);
            }
        }

        Log::error('All API strategies failed', ['endpoint' => $endpoint]);

        return '';
    }

    private function handleCurlError(int $errno, string $error, int $httpCode): void
    {
        Log::error('CURL Request Failed', [
            'curl_errno' => $errno,
            'curl_error' => $error,
            'http_code' => $httpCode,
        ]);

        match ($errno) {
            CURLE_SSL_CONNECT_ERROR, CURLE_SSL_PEER_CERTIFICATE, CURLE_SSL_CACERT => Log::error('SSL Certificate Error detected'),
            CURLE_COULDNT_RESOLVE_HOST => Log::error('DNS Resolution Error detected'),
            CURLE_OPERATION_TIMEDOUT => Log::error('Timeout Error detected'),
            CURLE_COULDNT_CONNECT => Log::error('Connection Error detected'),
            default => null,
        };
    }
}
