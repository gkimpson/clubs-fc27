<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public static function getDropdownOptions(bool $useCountryCode = false): array
    {
        return self::all()->mapWithKeys(function (self $country) use ($useCountryCode) {
            $label = $useCountryCode ? $country->code : $country->name;

            return [$country->id => $label];
        })->toArray();
    }
}
