<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->foreignId('activity_secondary_id')->nullable()->constrained('activities')->onDelete('set null');
        });

        Schema::table('mission_templates', function (Blueprint $table) {
            $table->foreignId('activity_secondary_id')->nullable()->constrained('activities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropColumn('activity_secondary_id');
        });

        Schema::table('mission_templates', function (Blueprint $table) {
            $table->dropColumn('activity_secondary_id');
        });
    }
};
