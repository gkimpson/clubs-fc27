<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum PlayerPositions: string
{
    use EnumToArray;

    case GOALKEEPER = 'GK';
    case CENTER_BACK = 'CB';
    case LEFT_BACK = 'LB';
    case LEFT_WING_BACK = 'LWB';
    case RIGHT_BACK = 'RB';
    case RIGHT_WING_BACK = 'RWB';
    case CENTRAL_DEFENSIVE_MIDFIELDER = 'CDM';
    case CENTRAL_ATTACKING_MIDFIELDER = 'CAM';
    case CENTRAL_MIDFIELDER = 'CM';
    case LEFT_MIDFIELDER = 'LM';
    case LEFT_WINGER = 'LW';
    case RIGHT_MIDFIELDER = 'RM';
    case RIGHT_WINGER = 'RW';
    case STRIKER = 'ST';
    case CENTRE_FORWARD = 'CF';
    case LEFT_FORWARD = 'LF';
    case RIGHT_FORWARD = 'RF';

    public function id(): int
    {
        return match ($this) {
            self::GOALKEEPER => 0,
            self::LEFT_BACK => 7,
            self::CENTER_BACK => 5,
            self::RIGHT_BACK => 3,
            self::LEFT_WING_BACK => 8,
            self::RIGHT_WING_BACK => 2,
            self::CENTRAL_DEFENSIVE_MIDFIELDER => 10,
            self::LEFT_MIDFIELDER => 16,
            self::CENTRAL_MIDFIELDER => 14,
            self::RIGHT_MIDFIELDER => 12,
            self::CENTRAL_ATTACKING_MIDFIELDER => 18,
            self::STRIKER => 25,
            self::LEFT_FORWARD => 22,
            self::CENTRE_FORWARD => 21,
            self::RIGHT_FORWARD => 20,
            self::LEFT_WINGER => 27,
            self::RIGHT_WINGER => 23,
        };
    }

    public static function fromId(int $id): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->id() === $id) {
                return $case;
            }
        }

        return null;
    }
}
