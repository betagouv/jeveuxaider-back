<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCollectivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collectivities', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
        });

        Schema::table('collectivities', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collectivities', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table('collectivities', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
    }
}
