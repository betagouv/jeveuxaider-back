<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateToMissionTemplateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mission_templates', function (Blueprint $table) {
            $table->string('state')->default('draft');
            $table->boolean('published')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mission_templates', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->boolean('published')->default(true)->change();
        });
    }
}
