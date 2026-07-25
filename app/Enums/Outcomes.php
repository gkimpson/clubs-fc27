<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum Outcomes: string
{
    use EnumToArray;

    case HOME_WIN = 'H';
    case AWAY_WIN = 'A';
    case DRAW = 'D';
}
