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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('notification__responsable_frequency')->default('realtime');
            $table->boolean('notification__responsable_bilan')->default(true);
            $table->string('notification__referent_frequency')->default('realtime');
            $table->boolean('notification__referent_bilan')->default(true);
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
            $table->dropColumn('notification__responsable_frequency');
            $table->dropColumn('notification__responsable_bilan');
            $table->dropColumn('notification__referent_frequency');
            $table->dropColumn('notification__referent_bilan');
        });
    }
};
