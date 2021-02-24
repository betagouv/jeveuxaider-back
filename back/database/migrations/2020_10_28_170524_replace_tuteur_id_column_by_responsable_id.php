<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceTuteurIdColumnByResponsableId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropForeign(['tuteur_id']);
            $table->dropIndex(['tuteur_id']);
            $table->renameColumn('tuteur_id', 'responsable_id');
            $table->foreign('responsable_id')
                ->references('id')
                ->on('profiles')
                ->onDelete('set null');
            $table->index(['responsable_id']);
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
            $table->dropForeign(['responsable_id']);
            $table->dropIndex(['responsable_id']);
            $table->renameColumn('responsable_id', 'tuteur_id');
            $table->foreign('tuteur_id')
                ->references('id')
                ->on('profiles')
                ->onDelete('set null');
            $table->index(['tuteur_id']);
        });
    }
}
