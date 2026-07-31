<?php

declare(strict_types=1);

namespace App\Services\EA;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClubsApiService
{
    public const API_URL = 'https://proclubs.ea.com/api/fc/';

    private bool $useCurl = false;

    public function setUseCurl(bool $useCurl): self
    {
        $this->useCurl = $useCurl;

        return $this;
    }

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
        if ($this->useCurl) {
            return $this->doCurlApiCall($endpoint, $params);
        }

        return $this->doHttpClientApiCall($endpoint, $params);
    }

    private function doCurlApiCall(string $endpoint, array $params = []): ?string
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
            Log::info('Trying curl API strategy: '.$strategy['name'], ['url' => $url]);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '', // Enable automatic decompression of gzip/deflate/br
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2,
                CURLOPT_HTTPHEADER => $strategy['headers'],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);

            Log::info('Curl strategy result', [
                'strategy' => $strategy['name'],
                'http_code' => $httpCode,
                'response_length' => is_string($response) ? strlen($response) : 0,
                'curl_error' => $curlError,
                'is_access_denied' => is_string($response) && strpos($response, 'Access Denied') !== false,
            ]);

            curl_close($ch);

            if ($httpCode === 200 && is_string($response) && ! empty($response) && strpos($response, 'Access Denied') === false) {
                Log::info('Curl strategy succeeded: '.$strategy['name']);

                return $response;
            }
        }

        Log::error('All curl API strategies failed', ['endpoint' => $endpoint]);

        return null;
    }

    private function doHttpClientApiCall(string $endpoint, array $params = []): ?string
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

    public function clubsInfo(string $platform, int $eaClubId): ?array
    {
        return $this->performApiCall('clubs/info', [
            'platform' => $platform,
            'clubIds' => $eaClubId,
        ]);
    }

    public function getMatchStats(string $platform, int $eaClubId, string $matchType, int $maxResultCount = 20): ?array
    {
        $maxResultCount = max(1, $maxResultCount);

        return $this->performApiCall('clubs/matches', [
            'matchType' => $matchType,
            'platform' => $platform,
            'clubIds' => $eaClubId,
            'maxResultCount' => $maxResultCount,
        ]);
    }

    public function memberStats(string $platform, int $eaClubId): ?array
    {
        return $this->performApiCall('members/stats', [
            'platform' => $platform,
            'clubId' => $eaClubId,
        ]);
    }

    public function careerStats(string $platform, int $eaClubId): ?array
    {
        return $this->performApiCall('members/career/stats', [
            'platform' => $platform,
            'clubId' => $eaClubId,
        ]);
    }

    public function seasonStats(string $platform, int $eaClubId): ?array
    {
        return $this->performApiCall('clubs/seasonalStats', [
            'platform' => $platform,
            'clubIds' => $eaClubId,
        ]);
    }

    public function settings(string $platform): ?array
    {
        return $this->performApiCall('settings', [
            'platform' => $platform,
        ]);
    }

    public function search(string $platform, string $clubName): ?array
    {
        return $this->performApiCall('allTimeLeaderboard/search', [
            'platform' => $platform,
            'clubName' => $clubName,
        ]);
    }

    public function leaderboard(string $platform, string $type): ?array
    {
        $endpoint = $type === 'club' ? 'clubRankLeaderboard' : 'seasonRankLeaderboard';

        return $this->performApiCall($endpoint, [
            'platform' => $platform,
        ]);
    }

    public function overallStats(string $platform, int $eaClubId): ?array
    {
        return $this->performApiCall('clubs/overallStats', [
            'platform' => $platform,
            'clubIds' => $eaClubId,
        ]);
    }

    public function playoffAchievements(string $platform, int $eaClubId): ?array
    {
        return $this->performApiCall('club/playoffAchievements', [
            'platform' => $platform,
            'clubId' => $eaClubId,
        ]);
    }

    public function compareCareerStats(string $platform, int $eaClubId1, int $eaClubId2): array
    {
        $club1 = $this->careerStats($platform, $eaClubId1);
        $club2 = $this->careerStats($platform, $eaClubId2);

        return [
            $eaClubId1 => $club1,
            $eaClubId2 => $club2,
        ];
    }

    public function compareMembersStats(string $platform, int $eaClubId1, int $eaClubId2): array
    {
        $club1 = $this->memberStats($platform, $eaClubId1);
        $club2 = $this->memberStats($platform, $eaClubId2);

        return [
            $eaClubId1 => $club1,
            $eaClubId2 => $club2,
        ];
    }

    public function compareClubsInfo(string $platform, int $eaClubId1, int $eaClubId2): array
    {
        $club1 = $this->clubsInfo($platform, $eaClubId1);
        $club2 = $this->clubsInfo($platform, $eaClubId2);

        return [
            $eaClubId1 => $club1,
            $eaClubId2 => $club2,
        ];
    }

    public function compareOverallStats(string $platform, int $eaClubId1, int $eaClubId2): array
    {
        $club1 = $this->overallStats($platform, $eaClubId1);
        $club2 = $this->overallStats($platform, $eaClubId2);

        return [
            $eaClubId1 => $club1,
            $eaClubId2 => $club2,
        ];
    }
}
