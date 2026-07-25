<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'fav_position',
        'acceleration',
        'aggression',
        'agility',
        'attack_position',
        'balance',
        'ball_control',
        'composure',
        'crossing',
        'curve',
        'dribbling',
        'finishing',
        'free_kick_accuracy',
        'gk_diving',
        'gk_handling',
        'gk_kicking',
        'gk_positioning',
        'gk_reflexes',
        'heading_accuracy',
        'interceptions',
        'jumping',
        'long_pass',
        'long_shots',
        'marking',
        'penalties',
        'reactions',
        'short_pass',
        'shot_power',
        'slide_tackle',
        'sprint_speed',
        'stamina',
        'stand_tackle',
        'strength',
        'vision',
        'volleys',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
