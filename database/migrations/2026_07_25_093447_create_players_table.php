<?php

use App\Enums\PerformanceTrendTypes;
use App\Enums\Platforms;
use App\Enums\PlayerPositionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id')->index();
            $table->string('name');
            $table->unsignedBigInteger('ea_player_id')->index();
            $table->enum('platform', Platforms::values())->index();
            $table->string('attributes')->nullable();
            $table->enum('position_type', PlayerPositionTypes::values())->index()->default(PlayerPositionTypes::FORWARD->value);

            $table->tinyInteger('ea_pro_position')->nullable();
            $table->unsignedTinyInteger('ea_pro_height')->nullable();
            $table->unsignedTinyInteger('ea_pro_nationality')->nullable();
            $table->unsignedTinyInteger('ea_pro_overall')->nullable();
            $table->enum('ea_pro_fav_position', PlayerPositionTypes::values())->nullable();
            $table->json('prev_goals')->nullable();
            $table->enum('performance_trend', PerformanceTrendTypes::values())->default(PerformanceTrendTypes::STABLE->value);

            $table->boolean('is_cheater')->default(false);
            $table->text('cheat_reason')->nullable();
            $table->timestamp('flagged_at')->nullable();

            $table->timestamps();

            $table->index(['ea_player_id', 'platform']);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
