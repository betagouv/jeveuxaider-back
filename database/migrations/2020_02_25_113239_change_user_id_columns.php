<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserIdColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->increments('id')->unsigned()->change();
        });

        Schema::table('missions', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->change();
        });

        Schema::table('structures', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->change();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->change();
        });

        Schema::table('missions', function (Blueprint $table) {
            $table->integer('user_id')->change();
        });

        Schema::table('structures', function (Blueprint $table) {
            $table->integer('user_id')->change();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('user_id')->change();
        });
    }
}
