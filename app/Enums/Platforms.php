<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum Platforms: string
{
    use EnumToArray;

    case CURRENT_GEN = 'common-gen5';
    case LAST_GEN = 'common-gen4';
    case SWITCH = 'nx';

    public static function getPlatform(string $platform): self
    {
        return match ($platform) {
            self::CURRENT_GEN->value => self::CURRENT_GEN,
            self::LAST_GEN->value => self::LAST_GEN,
            self::SWITCH->value => self::SWITCH,
            default => throw new \Exception(sprintf('Unexpected platform value: %s', $platform)),
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::CURRENT_GEN => 'Current Gen (PS5/Xbox Series/PC)',
            self::LAST_GEN => 'Last Gen (PS4/Xbox One)',
            self::SWITCH => 'Nintendo Switch',
        };
    }

    public static function toDropdownOptions(): array
    {
        return array_map(
            fn (self $platform) => [
                'value' => $platform->value,
                'label' => $platform->getLabel(),
            ],
            self::cases()
        );
    }
}
