<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedColumnsInMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropColumn([
                'domaines',
                'periodes',
                'frequence',
                'planning',
                'actions',
                'justifications',
                'contraintes',
                'handicap'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->json('domaines')->nullable();
            $table->json('periodes')->nullable();
            $table->text('frequence')->nullable();
            $table->json('planning')->nullable();
            $table->text('actions')->nullable();
            $table->text('justifications')->nullable();
            $table->text('contraintes')->nullable();
            $table->string('handicap')->nullable();
        });
    }
}
