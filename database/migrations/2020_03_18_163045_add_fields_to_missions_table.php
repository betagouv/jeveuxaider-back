<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->text('periodicite')->nullable();
            $table->json('publics_beneficiaires')->nullable();
            $table->json('publics_volontaires')->nullable();
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
            $table->dropColumn("periodicite");
            $table->dropColumn("publics_beneficiaires");
            $table->dropColumn("publics_volontaires");
        });
    }
}
