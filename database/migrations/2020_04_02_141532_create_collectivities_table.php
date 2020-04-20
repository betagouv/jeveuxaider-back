<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectivities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('type')->nullable();
            $table->string('department')->nullable();
            $table->json('zips')->nullable();
            $table->text('description')->nullable();
            $table->string('state');
            $table->timestamps();
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
        Schema::dropIfExists('collectivities');
    }
}
