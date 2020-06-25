<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToThematiquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thematiques', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('color')->default('blue-800');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thematiques', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('color');
        });
    }
}
