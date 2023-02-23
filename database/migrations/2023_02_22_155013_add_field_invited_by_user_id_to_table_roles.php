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
        Schema::table('rolables', function (Blueprint $table) {
            $table->unsignedBigInteger('invited_by_user_id')->nullable()->index();
            $table->foreign('invited_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rolables', function (Blueprint $table) {
            $table->dropForeign(['invited_by_user_id']);
            $table->dropColumn('invited_by_user_id');
        });
    }
};
