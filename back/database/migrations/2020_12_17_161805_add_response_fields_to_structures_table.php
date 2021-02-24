<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponseFieldsToStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->integer('response_ratio')->nullable()->after('name');
            $table->integer('response_time')->nullable()->after('name');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->integer('response_time')->nullable()->after('conversable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->dropColumn('response_ratio');
            $table->dropColumn('response_time');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('response_time');
        });
    }
}
