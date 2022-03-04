<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingIndexesOnVariousTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->index(['user_id']);
        });
        Schema::table('missions', function (Blueprint $table) {
            $table->index(['tuteur_id']);
        });
        Schema::table('structures', function (Blueprint $table) {
            $table->index(['user_id']);
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
            $table->dropIndex(['user_id']);
        });
        Schema::table('missions', function (Blueprint $table) {
            $table->dropIndex(['tuteur_id']);
        });
        Schema::table('structures', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
}
