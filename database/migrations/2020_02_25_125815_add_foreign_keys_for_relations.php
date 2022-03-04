<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysForRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('cascade');
            $table->foreign('tuteur_id')->references('id')->on('profiles')->onDelete('set null');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reseau_id')->references('id')->on('structures')->onDelete('set null');
        });

        Schema::table('structures', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
            $table->dropForeign(['user_id']);
            $table->dropForeign(['structure_id']);
            $table->dropForeign(['tuteur_id']);
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['reseau_id']);
        });
        Schema::table('structures', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}
