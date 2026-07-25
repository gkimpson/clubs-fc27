<?php

namespace Database\Factories;

use App\Models\JsonResultArchive;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JsonResultArchive>
 */
class JsonResultArchiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ea_result_id' => fake()->unique()->uuid(),
            'data' => [
                'match_id' => fake()->uuid(),
                'home_club_id' => fake()->numberBetween(1000000, 9999999),
                'away_club_id' => fake()->numberBetween(1000000, 9999999),
                'home_goals' => fake()->numberBetween(0, 5),
                'away_goals' => fake()->numberBetween(0, 5),
                'match_date' => fake()->dateTime()->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
