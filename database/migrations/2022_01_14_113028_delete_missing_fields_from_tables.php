<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteMissingFieldsFromTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
