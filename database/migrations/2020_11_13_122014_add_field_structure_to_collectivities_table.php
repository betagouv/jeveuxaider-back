<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldStructureToCollectivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collectivities', function (Blueprint $table) {
            $table->integer('structure_id')->unsigned()->nullable();
            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collectivities', function (Blueprint $table) {
            $table->dropForeign(['structure_id']);
            $table->dropColumn('structure_id');
        });
    }
}
