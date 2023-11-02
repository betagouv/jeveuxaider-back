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
        Schema::table('participations', function ($table) {
            $table->string('utm_campaign', 500)->change();
        });

        Schema::table('users', function ($table) {
            $table->string('utm_campaign', 500)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participations', function ($table) {
            $table->string('utm_campaign', 255)->change();
        });

        Schema::table('users', function ($table) {
            $table->string('utm_campaign', 255)->change();
        });
    }
};
