<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum MatchTypes: string
{
    use EnumToArray;

    case LEAGUE = 'leagueMatch';
    case PLAYOFF = 'playoffMatch';
    case FRIENDLY = 'friendlyMatch';

    public static function getMatchTypeById(string $matchTypeId): string
    {
        return match ($matchTypeId) {
            '1' => 'leagueMatch',
            '3' => 'playoffMatch',
            '5' => 'friendlyMatch',
            default => throw new \Exception('Unexpected match type value'),
        };
    }
}
