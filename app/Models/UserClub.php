<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserClub extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'club_id', 'ea_club_id', 'platform', 'last_scanned_at', 'suspended_at'];

    protected $casts = [
        'last_scanned_at' => 'datetime',
        'suspended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
