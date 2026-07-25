<?php

namespace Database\Factories;

use App\Enums\Platforms;
use App\Models\Club;
use App\Models\User;
use App\Models\UserClub;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserClub>
 */
class UserClubFactory extends Factory
{
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $club = Club::inRandomOrder()->first();

        return [
            'user_id' => $user?->id ?? User::factory(),
            'club_id' => $club?->id ?? Club::factory(),
            'ea_club_id' => fake()->unique()->numberBetween(1000000, 9999999),
            'platform' => fake()->randomElement(Platforms::values()),
            'last_scanned_at' => fake()->optional()->dateTime(),
            'suspended_at' => fake()->optional()->boolean(5) ? now() : null,
        ];
    }
}
