<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum PlayerAttributes: string
{
    use EnumToArray;

    case ACCELERATION = 'acceleration';
    case AGGRESSION = 'aggression';
    case AGILITY = 'agility';
    case ATTACK_POSITION = 'attack_position';
    case BALANCE = 'balance';
    case BALL_CONTROL = 'ball_control';
    case COMPOSURE = 'composure';
    case CROSSING = 'crossing';
    case CURVE = 'curve';
    case DRIBBLING = 'dribbling';
    case FINISHING = 'finishing';
    case FREE_KICK_ACCURACY = 'free_kick_accuracy';
    case GK_DIVING = 'gk_diving';
    case GK_HANDLING = 'gk_handling';
    case GK_KICKING = 'gk_kicking';
    case GK_POSITIONING = 'gk_positioning';
    case GK_REFLEXES = 'gk_reflexes';
    case HEADING_ACCURACY = 'heading_accuracy';
    case INTERCEPTIONS = 'interceptions';
    case JUMPING = 'jumping';
    case LONG_PASS = 'long_pass';
    case LONG_SHOTS = 'long_shots';
    case MARKING = 'marking';
    case PENALTIES = 'penalties';
    case REACTIONS = 'reactions';
    case SHORT_PASS = 'short_pass';
    case SHOT_POWER = 'shot_power';
    case SLIDE_TACKLE = 'slide_tackle';
    case SPRINT_SPEED = 'sprint_speed';
    case STAMINA = 'stamina';
    case STAND_TACKLE = 'stand_tackle';
    case STRENGTH = 'strength';
    case VISION = 'vision';
    case VOLLEYS = 'volleys';

    public static function getGKAttributes(): array
    {
        return [
            self::GK_DIVING->value,
            self::GK_HANDLING->value,
            self::GK_KICKING->value,
            self::GK_POSITIONING->value,
            self::GK_REFLEXES->value,
        ];
    }
}
