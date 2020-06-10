<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielsToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('is_visible')->default(false);
            $table->json('disponibilities')->nullable();
            $table->text('description')->nullable();
            $table->text('frequence')->nullable();
            $table->text('frequence_granularite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('is_visible');
            $table->dropColumn('disponibilities');
            $table->dropColumn('description');
            $table->dropColumn('frequence');
            $table->dropColumn('frequence_granularite');
        });
    }
}
