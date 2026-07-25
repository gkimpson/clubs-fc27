<?php

use App\Enums\PlayerAttributes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id')->index()->unique();
            $table->enum('fav_position', ['G', 'D', 'M', 'F', 'A'])->default('A');

            foreach (PlayerAttributes::values() as $attribute) {
                $table->unsignedTinyInteger($attribute)->nullable();
            }

            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_attributes');
    }
};
