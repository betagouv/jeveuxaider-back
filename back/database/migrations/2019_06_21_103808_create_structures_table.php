<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->boolean('is_reseau')->default(false);
            $table->integer('reseau_id')->unsigned()->nullable();
            $table->string('siret')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('description')->nullable();
            $table->string('statut_juridique')->nullable();
            $table->json('association_types')->nullable();
            $table->string('structure_publique_type')->nullable();
            $table->string('structure_publique_etat_type')->nullable();
            $table->string('structure_privee_type')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('department')->nullable();
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
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
        Schema::dropIfExists('structures');
    }
}
