<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum PlayerPositionTypes: string
{
    use EnumToArray;

    case ANY = 'A';
    case GOALKEEPER = 'G';
    case DEFENDER = 'D';
    case MIDFIELDER = 'M';
    case FORWARD = 'F';

    public function getLabel(): string
    {
        return match ($this) {
            self::ANY => 'Any',
            self::GOALKEEPER => 'Goalkeeper',
            self::DEFENDER => 'Defender',
            self::MIDFIELDER => 'Midfielder',
            self::FORWARD => 'Forward',
        };
    }

    public static function toDropdownOptions(): array
    {
        return array_map(
            fn (self $position) => [
                'value' => $position->value,
                'label' => $position->getLabel(),
            ],
            self::cases()
        );
    }
}
