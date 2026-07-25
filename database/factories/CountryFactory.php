<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $countries = [
            ['name' => 'England', 'code' => 'EN'],
            ['name' => 'Spain', 'code' => 'ES'],
            ['name' => 'Germany', 'code' => 'DE'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Italy', 'code' => 'IT'],
            ['name' => 'Portugal', 'code' => 'PT'],
            ['name' => 'Netherlands', 'code' => 'NL'],
            ['name' => 'Belgium', 'code' => 'BE'],
            ['name' => 'Brazil', 'code' => 'BR'],
            ['name' => 'Argentina', 'code' => 'AR'],
        ];

        static $index = 0;
        $country = $countries[$index % count($countries)];
        $index++;

        return $country;
    }
}
