<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_match_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id')->index();
            $table->unsignedTinyInteger('home_tackles_made')->default(0)->nullable();
            $table->unsignedTinyInteger('away_tackles_made')->default(0)->nullable();
            $table->unsignedTinyInteger('home_tackles_attempted')->default(0)->nullable();
            $table->unsignedTinyInteger('away_tackles_attempted')->default(0)->nullable();
            $table->unsignedTinyInteger('home_goals')->default(0)->nullable();
            $table->unsignedTinyInteger('away_goals')->default(0)->nullable();
            $table->unsignedTinyInteger('home_shots')->default(0)->nullable();
            $table->unsignedTinyInteger('away_shots')->default(0)->nullable();
            $table->unsignedTinyInteger('home_passes_made')->default(0)->nullable();
            $table->unsignedTinyInteger('away_passes_made')->default(0)->nullable();
            $table->unsignedTinyInteger('home_passes_attempted')->default(0)->nullable();
            $table->unsignedTinyInteger('away_passes_attempted')->default(0)->nullable();
            $table->unsignedTinyInteger('home_assists')->default(0)->nullable();
            $table->unsignedTinyInteger('away_assists')->default(0)->nullable();
            $table->unsignedTinyInteger('home_mom')->default(0)->nullable();
            $table->unsignedTinyInteger('away_mom')->default(0)->nullable();
            $table->unsignedTinyInteger('home_total_rating')->default(0)->nullable();
            $table->unsignedTinyInteger('away_total_rating')->default(0)->nullable();
            $table->unsignedTinyInteger('home_ave_rating')->default(0)->nullable();
            $table->unsignedTinyInteger('away_ave_rating')->default(0)->nullable();
            $table->unsignedTinyInteger('home_red_cards')->default(0)->nullable();
            $table->unsignedTinyInteger('away_red_cards')->default(0)->nullable();
            $table->unsignedTinyInteger('home_clean_sheets_gk')->default(0)->nullable();
            $table->unsignedTinyInteger('away_clean_sheets_gk')->default(0)->nullable();
            $table->unsignedTinyInteger('home_clean_sheets_def')->default(0)->nullable();
            $table->unsignedTinyInteger('away_clean_sheets_def')->default(0)->nullable();
            $table->unsignedTinyInteger('home_clean_sheets_any')->default(0)->nullable();
            $table->unsignedTinyInteger('away_clean_sheets_any')->default(0)->nullable();
            $table->unsignedTinyInteger('home_saves')->default(0)->nullable();
            $table->unsignedTinyInteger('away_saves')->default(0)->nullable();
            $table->unsignedTinyInteger('home_cpu_goals')->default(0)->nullable();
            $table->unsignedTinyInteger('away_cpu_goals')->default(0)->nullable();
            $table->unsignedTinyInteger('home_winner_by_dnf')->default(0)->nullable();
            $table->unsignedTinyInteger('away_winner_by_dnf')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_match_stats');
    }
};
