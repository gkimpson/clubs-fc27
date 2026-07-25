<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'ea_player_id',
        'platform',
        'attributes',
        'position_type',
        'ea_pro_position',
        'ea_pro_height',
        'ea_pro_nationality',
        'ea_pro_overall',
        'ea_pro_fav_position',
        'prev_goals',
        'performance_trend',
        'is_cheater',
        'cheat_reason',
        'flagged_at',
    ];

    protected $casts = [
        'is_cheater' => 'boolean',
        'flagged_at' => 'datetime',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function playerAttributes(): HasOne
    {
        return $this->hasOne(PlayerAttribute::class);
    }

    public function resultPlayerStats(): HasMany
    {
        return $this->hasMany(ResultPlayerStat::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'player_id', 'id');
    }

    public function scopeNonCheaters(Builder $query): Builder
    {
        return $query->where('is_cheater', false);
    }

    public function scopeCheaters(Builder $query): Builder
    {
        return $query->where('is_cheater', true);
    }

    public function flagAsCheater(?string $reason = null): void
    {
        $this->update([
            'is_cheater' => true,
            'cheat_reason' => $reason,
            'flagged_at' => now(),
        ]);
    }

    public function unflagAsCheater(): void
    {
        $this->update([
            'is_cheater' => false,
            'cheat_reason' => null,
            'flagged_at' => null,
        ]);
    }
}
