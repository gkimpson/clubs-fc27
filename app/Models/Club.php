<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ea_club_id', 'platform', 'badge_id', 'last_scanned_at', 'skill_rating'];

    protected $casts = [
        'last_scanned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function homeResults(): HasMany
    {
        return $this->hasMany(Result::class, 'home_club_id');
    }

    public function awayResults(): HasMany
    {
        return $this->hasMany(Result::class, 'away_club_id');
    }
}
