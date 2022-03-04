<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReseauStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseau_structure', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reseau_id')->unsigned()->index();
            $table->foreign('reseau_id')->references('id')->on('reseaux')->onDelete('cascade');
            $table->integer('structure_id')->unsigned()->index();
            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('cascade');
            $table->unique(['reseau_id', 'structure_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reseau_structure');
    }
}
