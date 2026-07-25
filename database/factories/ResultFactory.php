<?php

namespace Database\Factories;

use App\Enums\MatchTypes;
use App\Enums\Outcomes;
use App\Enums\Platforms;
use App\Models\Club;
use App\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Result>
 */
class ResultFactory extends Factory
{
    public function definition(): array
    {
        $homeGoals = fake()->numberBetween(0, 5);
        $awayGoals = fake()->numberBetween(0, 5);

        $outcome = match (true) {
            $homeGoals > $awayGoals => Outcomes::HOME_WIN,
            $awayGoals > $homeGoals => Outcomes::AWAY_WIN,
            default => Outcomes::DRAW,
        };

        return [
            'ea_result_id' => fake()->unique()->uuid(),
            'platform' => fake()->randomElement(Platforms::values()),
            'match_type' => fake()->randomElement(MatchTypes::values()),
            'home_club_id' => Club::inRandomOrder()->first()?->id ?? Club::factory(),
            'away_club_id' => Club::inRandomOrder()->first()?->id ?? Club::factory(),
            'home_goals' => $homeGoals,
            'away_goals' => $awayGoals,
            'outcome' => $outcome,
            'match_date' => fake()->dateTimeBetween('-6 months'),
            'media' => null,
            'properties' => null,
            'key_moments' => null,
            'highlights_url' => fake()->optional()->url(),
        ];
    }
}
