<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('player_id')->nullable()->after('email_verified_at');
            $table->unsignedBigInteger('active_club_id')->nullable()->after('player_id');
            $table->boolean('has_mic')->default(1)->after('active_club_id');
            $table->json('properties')->nullable()->after('has_mic');
            $table->timestamp('suspended_at')->nullable()->after('properties');
            $table->unsignedBigInteger('country_id')->nullable()->after('suspended_at');

            $table->foreign('active_club_id')->references('id')->on('clubs')->onDelete('set null');
            $table->index('active_club_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['active_club_id']);
            $table->dropIndex(['active_club_id']);
            $table->dropColumn('player_id', 'active_club_id', 'has_mic', 'properties', 'suspended_at', 'country_id');
        });
    }
};
