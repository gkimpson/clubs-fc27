<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Country;
use App\Models\Player;
use App\Models\PlayerAttribute;
use App\Models\Result;
use App\Models\ResultMatchStat;
use App\Models\ResultPlayerStat;
use App\Models\UserClub;
use Illuminate\Database\Seeder;

class ProClubsSeeder extends Seeder
{
    public function run(): void
    {
        Country::factory(10)->create();

        Club::factory(20)->create();

        $players = Player::factory(100)->create();

        $players->each(fn ($player) => PlayerAttribute::factory()->create(['player_id' => $player->id]));

        Result::factory(50)->create();

        ResultMatchStat::factory(50)->create();

        ResultPlayerStat::factory(200)->create();

        UserClub::factory(30)->create();
    }
}
