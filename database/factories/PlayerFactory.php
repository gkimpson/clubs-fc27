<?php

namespace Database\Factories;

use App\Enums\PerformanceTrendTypes;
use App\Enums\Platforms;
use App\Enums\PlayerPositionTypes;
use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    public function definition(): array
    {
        $club = Club::inRandomOrder()->first();

        return [
            'club_id' => $club !== null ? $club->id : Club::factory(),
            'name' => fake()->name(),
            'ea_player_id' => fake()->unique()->numberBetween(1000000, 9999999),
            'platform' => fake()->randomElement(Platforms::values()),
            'attributes' => null,
            'position_type' => fake()->randomElement(PlayerPositionTypes::values()),
            'ea_pro_position' => fake()->optional()->numberBetween(0, 27),
            'ea_pro_height' => fake()->optional()->numberBetween(160, 200),
            'ea_pro_nationality' => fake()->optional()->numberBetween(1, 200),
            'ea_pro_overall' => fake()->optional()->numberBetween(45, 99),
            'ea_pro_fav_position' => fake()->optional()->randomElement(PlayerPositionTypes::values()),
            'prev_goals' => null,
            'performance_trend' => fake()->randomElement(PerformanceTrendTypes::values()),
            'is_cheater' => false,
            'cheat_reason' => null,
            'flagged_at' => null,
        ];
    }
}
