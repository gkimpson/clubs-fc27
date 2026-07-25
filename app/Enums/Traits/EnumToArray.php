<?php

namespace App\Enums\Traits;

trait EnumToArray
{
    public static function toArray(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
