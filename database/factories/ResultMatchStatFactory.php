<?php

namespace Database\Factories;

use App\Models\Result;
use App\Models\ResultMatchStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResultMatchStat>
 */
class ResultMatchStatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'result_id' => Result::factory(),
            'home_tackles_made' => fake()->numberBetween(5, 20),
            'away_tackles_made' => fake()->numberBetween(5, 20),
            'home_tackles_attempted' => fake()->numberBetween(10, 30),
            'away_tackles_attempted' => fake()->numberBetween(10, 30),
            'home_goals' => fake()->numberBetween(0, 5),
            'away_goals' => fake()->numberBetween(0, 5),
            'home_shots' => fake()->numberBetween(5, 20),
            'away_shots' => fake()->numberBetween(5, 20),
            'home_passes_made' => fake()->numberBetween(150, 250),
            'away_passes_made' => fake()->numberBetween(150, 250),
            'home_passes_attempted' => fake()->numberBetween(180, 255),
            'away_passes_attempted' => fake()->numberBetween(180, 255),
            'home_assists' => fake()->numberBetween(0, 5),
            'away_assists' => fake()->numberBetween(0, 5),
            'home_mom' => fake()->numberBetween(0, 2),
            'away_mom' => fake()->numberBetween(0, 2),
            'home_total_rating' => fake()->numberBetween(60, 90),
            'away_total_rating' => fake()->numberBetween(60, 90),
            'home_ave_rating' => fake()->numberBetween(6, 9),
            'away_ave_rating' => fake()->numberBetween(6, 9),
            'home_red_cards' => fake()->numberBetween(0, 2),
            'away_red_cards' => fake()->numberBetween(0, 2),
            'home_clean_sheets_gk' => fake()->numberBetween(0, 1),
            'away_clean_sheets_gk' => fake()->numberBetween(0, 1),
            'home_clean_sheets_def' => fake()->numberBetween(0, 1),
            'away_clean_sheets_def' => fake()->numberBetween(0, 1),
            'home_clean_sheets_any' => fake()->numberBetween(0, 1),
            'away_clean_sheets_any' => fake()->numberBetween(0, 1),
            'home_saves' => fake()->numberBetween(0, 10),
            'away_saves' => fake()->numberBetween(0, 10),
            'home_cpu_goals' => fake()->numberBetween(0, 2),
            'away_cpu_goals' => fake()->numberBetween(0, 2),
            'home_winner_by_dnf' => fake()->numberBetween(0, 1),
            'away_winner_by_dnf' => fake()->numberBetween(0, 1),
        ];
    }
}
