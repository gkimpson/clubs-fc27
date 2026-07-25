<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum PerformanceTrendTypes: string
{
    use EnumToArray;

    case RISING = 'rising';
    case STABLE = 'stable';
    case DECLINE = 'decline';
    case WORLD_CLASS = 'world_class';
}
