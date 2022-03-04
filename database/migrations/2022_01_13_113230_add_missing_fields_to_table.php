<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->json('missing_fields')->nullable();
        });
        Schema::table('structures', function (Blueprint $table) {
            $table->json('missing_fields')->nullable();
        });
        Schema::table('reseaux', function (Blueprint $table) {
            $table->json('missing_fields')->nullable();
        });
        Schema::table('territoires', function (Blueprint $table) {
            $table->json('missing_fields')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('missing_fields');
        });
        Schema::table('structures', function (Blueprint $table) {
            $table->dropColumn('missing_fields');
        });
        Schema::table('reseaux', function (Blueprint $table) {
            $table->dropColumn('missing_fields');
        });
        Schema::table('territoires', function (Blueprint $table) {
            $table->dropColumn('missing_fields');
        });
    }
}
