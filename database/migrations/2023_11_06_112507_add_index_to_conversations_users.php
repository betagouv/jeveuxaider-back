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
        Schema::table('conversations_users', function (Blueprint $table) {
            $table->index(['user_id']);
            $table->index(['conversation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations_users', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['conversation_id']);
        });
    }
};
