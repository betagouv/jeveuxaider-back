<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenameAvisTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('avis', 'temoignages');
        Schema::rename('notifications_avis', 'notifications_temoignages');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('temoignages', 'avis');
        Schema::rename('notifications_temoignages', 'notifications_avis');
    }
}
