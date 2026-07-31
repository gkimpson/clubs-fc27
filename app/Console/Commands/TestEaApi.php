<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\EA\ClubsApiService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('test:ea-api {--club-id=5706062 : Club ID to test} {--platform=common-gen5 : Platform} {--endpoint=all : Endpoint to test} {--use-curl : Use curl instead of Laravel HTTP client}')]
#[Description('Test EA API endpoints and data retrieval (idempotent)')]
class TestEaApi extends Command
{
    public function __construct(
        private ClubsApiService $apiService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $clubId = (int) $this->option('club-id');
        $platform = $this->option('platform');
        $endpoint = $this->option('endpoint');
        $useCurl = $this->option('use-curl');

        if ($useCurl) {
            $this->apiService->setUseCurl(true);
        }

        $this->info('Testing EA API endpoints...');
        $this->line("Club ID: <fg=cyan>{$clubId}</>");
        $this->line("Platform: <fg=cyan>{$platform}</>");
        $this->line("Endpoint(s): <fg=cyan>{$endpoint}</>");
        $this->line('Method: <fg=cyan>'.($useCurl ? 'curl' : 'Laravel HTTP').'</>');
        $this->newLine();

        $endpoints = $endpoint === 'all'
            ? ['matches', 'clubs-info', 'overall-stats', 'search', 'settings', 'member-stats', 'career-stats']
            : [$endpoint];

        $successCount = 0;
        foreach ($endpoints as $ep) {
            if ($this->testEndpoint($ep, $platform, $clubId)) {
                $successCount++;
            }
            $this->newLine();
        }

        $this->info("<fg=green>✓ {$successCount}/".count($endpoints).' endpoints working correctly!</>');

        return self::SUCCESS;
    }

    private function testEndpoint(string $endpoint, string $platform, int $clubId): bool
    {
        $this->info("→ Testing: $endpoint");

        $response = match ($endpoint) {
            'matches' => $this->apiService->performApiCall('clubs/matches', [
                'platform' => $platform,
                'clubIds' => $clubId,
                'matchType' => 'leagueMatch',
                'maxResultCount' => 1,
            ]),
            'clubs-info' => $this->apiService->clubsInfo($platform, $clubId),
            'overall-stats' => $this->apiService->overallStats($platform, $clubId),
            'member-stats' => $this->apiService->memberStats($platform, $clubId),
            'career-stats' => $this->apiService->careerStats($platform, $clubId),
            'search' => $this->apiService->search($platform, 'Banterbury'),
            'settings' => $this->apiService->settings($platform),
            'leaderboard' => $this->apiService->leaderboard($platform, 'club'),
            default => null,
        };

        if (! is_array($response)) {
            $this->error('  ✗ API returned null/empty response or response is not an array');

            return false;
        }

        $count = count($response);
        $this->line('  <fg=green>✓ HTTP request successful</>');
        $this->line("  <fg=green>✓ Response decoded to array ($count item".($count !== 1 ? 's' : '').')</>');

        if ($count === 0) {
            $this->warn('  ⚠ Response array is empty');

            return true;
        }

        $firstItem = reset($response);
        if (is_array($firstItem)) {
            $keys = count($firstItem);
            $this->line("  <fg=green>✓ First item has $keys keys</>");
        }

        return true;
    }
}
