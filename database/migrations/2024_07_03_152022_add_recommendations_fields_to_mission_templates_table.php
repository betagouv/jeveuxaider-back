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
        Schema::table('mission_templates', function (Blueprint $table) {
            $table->string('recommendation_date_type')->nullable();
            $table->boolean('recommendation_with_dates')->nullable();
            $table->string('recommendation_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_templates', function (Blueprint $table) {
            $table->dropColumn('recommendation_date_type');
            $table->dropColumn('recommendation_with_dates');
            $table->dropColumn('recommendation_type');
        });
    }
};
