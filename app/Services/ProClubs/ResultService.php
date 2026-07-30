<?php

declare(strict_types=1);

namespace App\Services\ProClubs;

use App\Enums\Outcomes;
use App\Models\Club;
use App\Models\Result;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ResultService
{
    public function insertResults(array $matchStats, string $platform): void
    {
        try {
            collect($matchStats)
                ->reverse()
                ->map(fn ($result) => $this->processMatchResult($result, $platform))
                ->all();
        } catch (Exception $e) {
            Log::error(sprintf('Error inserting results: %s', $e->getMessage()));
        }
    }

    private function processMatchResult(array $result, string $platform): ?array
    {
        $homeClub = $result['clubs'][0] ?? null;
        $awayClub = $result['clubs'][1] ?? null;

        if (! $homeClub || ! $awayClub) {
            return null;
        }

        $homeEaClubId = (int) ($result['clubs'][0]['clubId'] ?? 0);
        $awayEaClubId = (int) ($result['clubs'][1]['clubId'] ?? 0);

        if (empty($homeEaClubId) || empty($awayEaClubId)) {
            return null;
        }

        $homeClubId = $this->fetchClubId($platform, $homeEaClubId);
        $awayClubId = $this->fetchClubId($platform, $awayEaClubId);

        if (empty($homeClubId) || empty($awayClubId)) {
            return null;
        }

        if (Result::where('ea_result_id', $result['match_id'])
            ->where('platform', $platform)
            ->exists()) {
            return null;
        }

        $matchOutcome = $this->resolveMatchOutcome($homeClub);
        $matchDate = Carbon::createFromTimestamp($result['timestamp'])->format('Y-m-d H:i:s');

        $resultData = [
            'ea_result_id' => $result['match_id'],
            'platform' => $platform,
            'match_type' => $result['matchType'] ?? 'unknown',
            'home_club_id' => $homeClubId,
            'away_club_id' => $awayClubId,
            'home_goals' => $homeClub['goals'] ?? 0,
            'away_goals' => $awayClub['goals'] ?? 0,
            'outcome' => $matchOutcome,
            'match_date' => $matchDate,
        ];

        Result::create($resultData);

        return $result;
    }

    private function fetchClubId(string $platform, int $eaClubId): ?int
    {
        return Club::where('platform', $platform)
            ->where('ea_club_id', $eaClubId)
            ->value('id');
    }

    private function resolveMatchOutcome(array $homeClub): string
    {
        $outcome = $this->getMatchOutcome($homeClub);

        if ($outcome !== null) {
            return $outcome;
        }

        return $this->getOutcomeFromGoals($homeClub);
    }

    private function getMatchOutcome(array $clubData): ?string
    {
        return match (true) {
            (string) ($clubData['wins'] ?? 0) === '1' => Outcomes::HOME_WIN->value,
            (string) ($clubData['losses'] ?? 0) === '1' => Outcomes::AWAY_WIN->value,
            (string) ($clubData['ties'] ?? 0) === '1' => Outcomes::DRAW->value,
            default => null,
        };
    }

    private function getOutcomeFromGoals(array $homeClub): string
    {
        $homeGoals = $homeClub['goals'] ?? 0;
        $awayGoals = $homeClub['goalsAgainst'] ?? 0;

        return match (true) {
            $homeGoals > $awayGoals => Outcomes::HOME_WIN->value,
            $homeGoals < $awayGoals => Outcomes::AWAY_WIN->value,
            default => Outcomes::DRAW->value,
        };
    }
}
