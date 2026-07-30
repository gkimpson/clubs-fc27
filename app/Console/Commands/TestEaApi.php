<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\EA\ClubsApiService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('test:ea-api {--club-id=5706062 : Club ID to test} {--platform=common-gen5 : Platform}')]
#[Description('Test EA API connectivity and data retrieval (idempotent)')]
class TestEaApi extends Command
{
    public function __construct(
        private ClubsApiService $apiService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $clubId = $this->option('club-id');
        $platform = $this->option('platform');

        $this->info('Testing EA API endpoint...');
        $this->line("Club ID: <fg=cyan>{$clubId}</>");
        $this->line("Platform: <fg=cyan>{$platform}</>");
        $this->newLine();

        $this->info('→ Calling: clubs/matches');

        $response = $this->apiService->performApiCall('clubs/matches', [
            'platform' => $platform,
            'clubIds' => $clubId,
            'matchType' => 'leagueMatch',
            'maxResultCount' => 1,
        ]);

        if (! $response) {
            $this->error('✗ API returned null/empty response');

            return self::FAILURE;
        }

        $this->line('<fg=green>✓ HTTP request successful</>');

        if (! is_array($response)) {
            $this->error('✗ Response is not an array: '.gettype($response));

            return self::FAILURE;
        }

        $this->line('<fg=green>✓ Response decoded to array</>');

        $count = count($response);
        if ($count === 0) {
            $this->warn('⚠ Response array is empty (no matches found for this club)');

            return self::SUCCESS;
        }

        $this->line("<fg=green>✓ Found {$count} match(es)</>");

        $firstMatch = $response[0];
        $this->newLine();
        $this->info('First match details:');

        $this->table(
            ['Field', 'Value'],
            [
                ['Match ID', $firstMatch['matchId'] ?? 'N/A'],
                ['Timestamp', $firstMatch['timestamp'] ?? 'N/A'],
                ['Clubs', count($firstMatch['clubs'] ?? []).' clubs'],
                ['Players', count($firstMatch['players'] ?? []).' players'],
                ['Has Aggregate', isset($firstMatch['aggregate']) ? 'Yes' : 'No'],
            ]
        );

        $this->newLine();
        $this->info('<fg=green>✓ EA API is working correctly!</>');

        return self::SUCCESS;
    }
}
