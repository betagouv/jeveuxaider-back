<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerritoireRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territoire_relations', function (Blueprint $table) {
            $table->integer('territoire_id')->unsigned();
            $table->morphs('relation');

            $table->unique(['territoire_id', 'relation_id', 'relation_type']);

            $table->foreign('territoire_id')->references('id')->on('territoires')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('territoire_relations');
    }
}
