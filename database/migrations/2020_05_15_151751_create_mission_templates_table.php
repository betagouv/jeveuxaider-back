<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('objectif')->nullable();
            $table->text('description')->nullable();
            $table->integer('thematique_id')->unsigned()->nullable();
            $table->boolean('priority')->default(false);
            $table->boolean('published')->default(true);
            $table->timestamps();

            $table->index(['thematique_id']);
            $table->foreign('thematique_id')->references('id')->on('thematiques')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mission_templates');
    }
}
