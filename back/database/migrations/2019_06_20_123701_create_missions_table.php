<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->integer('structure_id')->unsigned();
            $table->integer('tuteur_id')->unsigned()->nullable();
            $table->json('domaines')->nullable();
            $table->integer('participations_max')->nullable();
            $table->string('format')->nullable();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->text('dates_infos')->nullable();

            $table->json('periodes')->nullable();
            $table->text('frequence')->nullable();
            $table->json('planning')->nullable();

            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('department')->nullable();
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->text('actions')->nullable();
            $table->text('justifications')->nullable();
            $table->text('contraintes')->nullable();
            $table->string('handicap')->nullable();

            $table->string('state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('missions');
    }
}
