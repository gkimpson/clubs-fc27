<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Country;
use App\Models\Player;
use App\Models\User;
use App\Models\UserClub;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BanterburySeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::firstOrCreate(
            ['code' => 'GB'],
            ['name' => 'United Kingdom'],
        );

        $banterbury = Club::firstOrCreate(
            ['ea_club_id' => '87976'],
            [
                'name' => 'Banterbury',
                'platform' => 'common-gen5',
                'badge_id' => '11',
            ],
        );

        $user = User::firstOrCreate(
            ['email' => 'gkimpson@gmail.com'],
            [
                'name' => 'Gavin',
                'password' => Hash::make('password'),
                'country_id' => $country->id,
                'active_club_id' => $banterbury->id,
                'has_mic' => true,
            ],
        );

        $player = Player::firstOrCreate(
            ['ea_player_id' => '344643278', 'platform' => 'common-gen5'],
            [
                'club_id' => $banterbury->id,
                'name' => 'zabius-uk',
                'attributes' => '083|088|093|096|078|085|065|092|066|091|064|085|097|099|091|090|086|080|071|095|098|069|077|082|067|065|076|096|077|010|010|010|010|010|',
                'position_type' => 'M',
            ],
        );

        $user->update(['player_id' => $player->id]);

        UserClub::firstOrCreate(
            ['user_id' => $user->id, 'club_id' => $banterbury->id],
            [
                'ea_club_id' => $banterbury->ea_club_id,
                'platform' => $banterbury->platform,
            ],
        );
    }
}
