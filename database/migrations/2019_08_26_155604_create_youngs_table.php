<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youngs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('department')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('regular_city')->nullable();
            $table->string('regular_latitude')->nullable();
            $table->string('regular_longitude')->nullable();
            $table->string('engaged')->nullable();
            $table->text('engaged_structure')->nullable();
            $table->string('interet_defense')->nullable();
            $table->string('interet_defense_type')->nullable();
            $table->string('interet_defense_domaine')->nullable();
            $table->text('interet_defense_motivation')->nullable();
            $table->string('interet_securite')->nullable();
            $table->string('interet_securite_domaine')->nullable();
            $table->string('interet_solidarite')->nullable();
            $table->string('interet_sante')->nullable();
            $table->string('interet_education')->nullable();
            $table->string('interet_culture')->nullable();
            $table->string('interet_sport')->nullable();
            $table->string('interet_environnement')->nullable();
            $table->string('interet_citoyennete')->nullable();
            $table->string('mission_format')->nullable();
            $table->text('mission_autonome_projet')->nullable();
            $table->string('mission_autonome_structure')->nullable();
            $table->text('contraintes')->nullable();
            $table->string('situation')->nullable();
            $table->string('genre')->nullable();
            $table->text('notes')->nullable();
            $table->integer('mission_id')->unsigned()->nullable();
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
        Schema::dropIfExists('youngs');
    }
}
