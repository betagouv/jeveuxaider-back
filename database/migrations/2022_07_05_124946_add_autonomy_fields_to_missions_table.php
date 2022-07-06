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
        Schema::table('missions', function (Blueprint $table) {
            $table->boolean('is_autonomy')->default(FALSE);
            $table->json('autonomy_zips')->nullable();
            $table->text('autonomy_precisions')->nullable();
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
            $table->dropColumn(('is_autonomy'));
            $table->dropColumn(('autonomy_zips'));
            $table->dropColumn(('autonomy_precisions'));
        });
    }
};
