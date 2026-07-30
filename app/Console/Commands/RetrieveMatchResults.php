<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Club;
use App\Models\User;
use App\Services\EA\ClubsApiService;
use App\Services\ProClubs\ResultService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature(
    'app:retrieve-match-results {--club-id= : The EA Club ID} {--platform=common-gen5 : The platform (default: common-gen5)} {--user= : User email to retrieve results for}'
)]
#[Description('Retrieve match results from EA API for a Pro Clubs team')]
class RetrieveMatchResults extends Command
{
    public function __construct(
        private ClubsApiService $apiService,
        private ResultService $resultService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $eaClubId = $this->option('club-id');
        $platform = $this->option('platform');
        $userEmail = $this->option('user');

        if (! $eaClubId && ! $userEmail) {
            $this->error('Provide either --club-id or --user option');

            return self::FAILURE;
        }

        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();
            if (! $user || ! $user->active_club_id) {
                $this->error('User not found or has no active club');

                return self::FAILURE;
            }

            $club = $user->clubs()->where('clubs.platform', $platform)->first();
            if (! $club) {
                $this->error("User has no club on platform: {$platform}");

                return self::FAILURE;
            }

            $eaClubId = $club->ea_club_id;
        } else {
            $club = Club::where('ea_club_id', $eaClubId)
                ->where('platform', $platform)
                ->first();

            if (! $club) {
                $this->error("Club not found: {$eaClubId} on {$platform}");

                return self::FAILURE;
            }
        }

        $this->info("Retrieving match results for club: {$eaClubId} on platform: {$platform}");

        $matchData = $this->apiService->performApiCall(
            "club/{$platform}/{$eaClubId}/matches",
        );

        if (! $matchData || ! isset($matchData['matches'])) {
            $this->error('Failed to retrieve match data from EA API');

            return self::FAILURE;
        }

        $resultCount = count($matchData['matches']);
        $this->info("Found {$resultCount} matches");

        $this->resultService->insertResults($matchData['matches'], $platform);

        $this->info('Match results retrieved and stored successfully');

        return self::SUCCESS;
    }
}
