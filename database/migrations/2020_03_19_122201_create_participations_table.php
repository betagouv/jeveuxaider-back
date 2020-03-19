<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('profile_id')->unsigned();
            $table->integer('mission_id')->unsigned();
            $table->timestamps();

            // $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('set null');
            // $table->foreign('mission_id')->references('id')->on('missions')->onDelete('set null');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participations');
    }
}
