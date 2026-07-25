<?php

namespace Database\Factories;

use App\Enums\Platforms;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Club>
 */
class ClubFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'ea_club_id' => fake()->unique()->numberBetween(1000000, 9999999),
            'platform' => fake()->randomElement(Platforms::values()),
            'badge_id' => fake()->optional()->numberBetween(1, 500),
            'last_scanned_at' => fake()->optional()->dateTime(),
            'skill_rating' => fake()->optional()->numberBetween(800, 2000),
        ];
    }
}
