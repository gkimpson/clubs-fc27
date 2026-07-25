<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'ea_result_id',
        'platform',
        'match_type',
        'home_club_id',
        'away_club_id',
        'home_goals',
        'away_goals',
        'outcome',
        'match_date',
        'media',
        'properties',
        'key_moments',
        'highlights_url',
    ];

    protected $casts = [
        'key_moments' => 'array',
        'properties' => 'array',
    ];

    public function homeClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'home_club_id');
    }

    public function awayClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'away_club_id');
    }

    public function resultMatchStats(): HasOne
    {
        return $this->hasOne(ResultMatchStat::class)->withDefault();
    }

    public function resultPlayerStats(): HasMany
    {
        return $this->hasMany(ResultPlayerStat::class);
    }

    public function matchDate(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->attributes['match_date'])->format('F j, Y, g:i a'),
        );
    }
}
