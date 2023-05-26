<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structures_scores', function (Blueprint $table) {
            $table->unsignedBigInteger('structure_id')->index();
            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('cascade');
            $table->unique(['structure_id']);
            $table->decimal('engagement_points');
            $table->decimal('reactivity_points');
            $table->decimal('bonus_points');
            $table->decimal('total_points');
            $table->decimal('response_ratio')->nullable();
            $table->integer('response_time')->nullable();
            $table->integer('nb_last_participations');
            $table->integer('nb_last_participations_with_response');
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
        Schema::dropIfExists('structures_scores');
    }
};
