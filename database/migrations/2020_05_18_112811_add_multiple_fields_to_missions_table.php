<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleFieldsToMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->text('objectif')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedInteger('domaine_id')->nullable();
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
            $table->dropColumn('objectif');
            $table->dropColumn('description');
            $table->dropColumn('template_id');
            $table->dropColumn('domaine_id');
        });
    }
}
