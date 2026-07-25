<?php

use App\Enums\MatchTypes;
use App\Enums\Outcomes;
use App\Enums\Platforms;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('ea_result_id')->unique();
            $table->enum('platform', Platforms::values())->index();
            $table->enum('match_type', MatchTypes::values())->index();
            $table->unsignedBigInteger('home_club_id')->index();
            $table->unsignedBigInteger('away_club_id')->index();
            $table->unsignedTinyInteger('home_goals')->default(0);
            $table->unsignedTinyInteger('away_goals')->default(0);
            $table->enum('outcome', Outcomes::values());
            $table->timestamp('match_date');
            $table->string('media')->nullable();
            $table->json('properties')->nullable();
            $table->json('key_moments')->nullable();
            $table->string('highlights_url')->nullable();

            $table->timestamps();

            $table->index(['home_club_id', 'away_club_id', 'match_date'], 'idx_results_club_date');
            $table->foreign('home_club_id')->references('id')->on('clubs');
            $table->foreign('away_club_id')->references('id')->on('clubs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
