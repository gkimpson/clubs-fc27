<?php

namespace Database\Factories;

use App\Enums\Platforms;
use App\Enums\PlayerPositionTypes;
use App\Models\Club;
use App\Models\Player;
use App\Models\Result;
use App\Models\ResultPlayerStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResultPlayerStat>
 */
class ResultPlayerStatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'result_id' => Result::factory(),
            'player_id' => Player::factory(),
            'club_id' => Club::factory(),
            'platform' => fake()->randomElement(Platforms::values()),
            'goals' => fake()->numberBetween(0, 3),
            'assists' => fake()->numberBetween(0, 2),
            'wins' => fake()->numberBetween(0, 3),
            'losses' => fake()->numberBetween(0, 3),
            'draws' => fake()->numberBetween(0, 3),
            'mom' => fake()->numberBetween(0, 1),
            'rating' => fake()->randomFloat(2, 4, 10),
            'shots' => fake()->numberBetween(0, 5),
            'tackles_made' => fake()->numberBetween(0, 10),
            'tackles_attempted' => fake()->numberBetween(0, 15),
            'passes_made' => fake()->numberBetween(10, 80),
            'passes_attempted' => fake()->numberBetween(15, 100),
            'red_cards' => fake()->numberBetween(0, 1),
            'clean_sheets_gk' => fake()->numberBetween(0, 1),
            'clean_sheets_def' => fake()->numberBetween(0, 1),
            'clean_sheets_any' => fake()->numberBetween(0, 1),
            'goals_conceded' => fake()->numberBetween(0, 3),
            'saves' => fake()->numberBetween(0, 5),
            'ball_dive_saves' => fake()->numberBetween(0, 3),
            'cross_saves' => fake()->numberBetween(0, 2),
            'good_direction_saves' => fake()->numberBetween(0, 2),
            'parry_saves' => fake()->numberBetween(0, 2),
            'punch_saves' => fake()->numberBetween(0, 2),
            'reflex_saves' => fake()->numberBetween(0, 2),
            'game_time' => fake()->numberBetween(0, 90),
            'seconds_played' => fake()->numberBetween(0, 5400),
            'realtime_game' => fake()->numberBetween(1000, 10000),
            'realtime_idle' => fake()->numberBetween(100, 2000),
            'match_event_aggregate_0' => null,
            'match_event_aggregate_1' => null,
            'match_event_aggregate_2' => null,
            'match_event_aggregate_3' => null,
            'archetype_id' => fake()->optional()->numberBetween(1, 13),
            'position' => fake()->randomElement(PlayerPositionTypes::values()),
        ];
    }
}
