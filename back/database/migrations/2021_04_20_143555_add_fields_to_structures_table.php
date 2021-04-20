<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->text('rna')->nullable();
            $table->text('donation')->nullable();
            $table->json('publics_beneficiaires')->default('[]');
            $table->text('image_1')->nullable();
            $table->text('image_2')->nullable();
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
            $table->dropColumn('rna');
            $table->dropColumn('donation');
            $table->dropColumn('publics_beneficiaires');
            $table->dropColumn('image_1');
            $table->dropColumn('image_2');
        });
    }
}
