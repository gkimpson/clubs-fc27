<?php

use App\Enums\Platforms;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('TEAM DISBANDED');
            $table->unsignedBigInteger('ea_club_id');
            $table->enum('platform', Platforms::values())->index();
            $table->unsignedInteger('badge_id')->nullable();
            $table->timestamp('last_scanned_at')->nullable();
            $table->unsignedSmallInteger('skill_rating')->nullable();
            $table->timestamps();

            $table->unique(['ea_club_id', 'platform']);
            $table->index(['ea_club_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
