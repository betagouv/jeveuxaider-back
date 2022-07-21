<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleToThematiquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thematiques', function (Blueprint $table) {
            $table->string('title')->default('Rejoignez JeVeuxAider dans votre domaine daction');
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
            $table->dropColumn('title');
        });
    }
}
