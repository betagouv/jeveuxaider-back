<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('missions_users_waiting_list', function (Blueprint $table) {
            $table->softDeletes();
            $table->dropUnique(['user_id', 'mission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missions_users_waiting_list', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->unique(['user_id', 'mission_id']);
        });
    }
};
