<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('json_result_archives', function (Blueprint $table) {
            $table->id();
            $table->string('ea_result_id')->unique();
            $table->json('data');
            $table->timestamps();

            $table->index('ea_result_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('json_result_archives');
    }
};
