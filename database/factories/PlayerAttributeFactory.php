<?php

namespace Database\Factories;

use App\Enums\PlayerAttributes;
use App\Models\Player;
use App\Models\PlayerAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlayerAttribute>
 */
class PlayerAttributeFactory extends Factory
{
    public function definition(): array
    {
        $player = Player::inRandomOrder()->first();
        $attributes = [
            'player_id' => $player?->id ?? Player::factory(),
            'fav_position' => fake()->randomElement(['G', 'D', 'M', 'F', 'A']),
        ];

        foreach (PlayerAttributes::values() as $attribute) {
            $attributes[$attribute] = fake()->optional()->numberBetween(30, 99);
        }

        return $attributes;
    }
}
