<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultPlayerStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'player_id',
        'club_id',
        'platform',
        'goals',
        'assists',
        'wins',
        'losses',
        'draws',
        'mom',
        'rating',
        'shots',
        'tackles_made',
        'tackles_attempted',
        'passes_made',
        'passes_attempted',
        'red_cards',
        'clean_sheets_gk',
        'clean_sheets_def',
        'clean_sheets_any',
        'goals_conceded',
        'saves',
        'ball_dive_saves',
        'cross_saves',
        'good_direction_saves',
        'parry_saves',
        'punch_saves',
        'reflex_saves',
        'game_time',
        'seconds_played',
        'realtime_game',
        'realtime_idle',
        'match_event_aggregate_0',
        'match_event_aggregate_1',
        'match_event_aggregate_2',
        'match_event_aggregate_3',
        'archetype_id',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'match_event_aggregate_0' => 'array',
            'match_event_aggregate_1' => 'array',
            'match_event_aggregate_2' => 'array',
            'match_event_aggregate_3' => 'array',
        ];
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function getClubName(): string
    {
        return $this->club->name;
    }

    #[Attribute]
    public function isTopPerformer(): bool
    {
        return $this->rating > 9;
    }
}
