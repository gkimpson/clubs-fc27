<?php

use App\Enums\PlayerPositionTypes;
use App\Enums\Platforms;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_player_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('club_id')->index();
            $table->enum('platform', Platforms::values());
            $table->unsignedTinyInteger('goals')->default(0)->nullable();
            $table->unsignedTinyInteger('assists')->default(0)->nullable();
            $table->unsignedTinyInteger('wins')->default(0)->nullable();
            $table->unsignedTinyInteger('losses')->default(0)->nullable();
            $table->unsignedTinyInteger('draws')->default(0)->nullable();
            $table->unsignedTinyInteger('mom')->default(0)->nullable();
            $table->float('rating')->default(0)->nullable();
            $table->unsignedTinyInteger('shots')->default(0)->nullable();
            $table->unsignedTinyInteger('tackles_made')->default(0)->nullable();
            $table->unsignedTinyInteger('tackles_attempted')->default(0)->nullable();
            $table->unsignedTinyInteger('passes_made')->default(0)->nullable();
            $table->unsignedTinyInteger('passes_attempted')->default(0)->nullable();
            $table->unsignedTinyInteger('red_cards')->default(0)->nullable();
            $table->unsignedTinyInteger('clean_sheets_gk')->default(0)->nullable();
            $table->unsignedTinyInteger('clean_sheets_def')->default(0)->nullable();
            $table->unsignedTinyInteger('clean_sheets_any')->default(0)->nullable();
            $table->unsignedTinyInteger('goals_conceded')->default(0)->nullable();
            $table->unsignedTinyInteger('saves')->default(0)->nullable();

            $table->unsignedTinyInteger('ball_dive_saves')->default(0)->nullable();
            $table->unsignedTinyInteger('cross_saves')->default(0)->nullable();
            $table->unsignedTinyInteger('good_direction_saves')->default(0)->nullable();
            $table->unsignedTinyInteger('parry_saves')->default(0)->nullable();
            $table->unsignedTinyInteger('punch_saves')->default(0)->nullable();
            $table->unsignedTinyInteger('reflex_saves')->default(0)->nullable();

            $table->unsignedInteger('game_time')->default(0)->nullable();
            $table->unsignedInteger('seconds_played')->default(0)->nullable();
            $table->unsignedInteger('realtime_game')->default(0)->nullable();
            $table->unsignedInteger('realtime_idle')->default(0)->nullable();

            $table->json('match_event_aggregate_0')->nullable();
            $table->json('match_event_aggregate_1')->nullable();
            $table->json('match_event_aggregate_2')->nullable();
            $table->json('match_event_aggregate_3')->nullable();

            $table->unsignedBigInteger('archetype_id')->nullable();

            $table->enum('position', PlayerPositionTypes::values());
            $table->timestamps();

            $table->index(['result_id', 'player_id']);
            $table->index(['player_id', 'rating']);

            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_player_stats');
    }
};
