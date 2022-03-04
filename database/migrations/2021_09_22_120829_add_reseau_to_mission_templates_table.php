<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReseauToMissionTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mission_templates', function (Blueprint $table) {
            $table->bigInteger('reseau_id')->unsigned()->nullable()->index();
            $table->foreign('reseau_id')->references('id')->on('reseaux')->onDelete('cascade');
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
            $table->dropColumn('reseau_id');
        });
    }
}
