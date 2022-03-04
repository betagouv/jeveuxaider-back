<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToReseauxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reseaux', function (Blueprint $table) {
            $table->json('publics_beneficiaires')->default("[]");
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('department')->nullable();
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->text('donation')->nullable();
            $table->text('image_1')->nullable();
            $table->text('image_2')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reseaux', function (Blueprint $table) {
            $table->dropColumn("publics_beneficiaires");
            $table->dropColumn("description");
            $table->dropColumn("phone");
            $table->dropColumn("email");
            $table->dropColumn('address');
            $table->dropColumn('zip');
            $table->dropColumn('city');
            $table->dropColumn('department');
            $table->dropColumn('country');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('website');
            $table->dropColumn('facebook');
            $table->dropColumn('twitter');
            $table->dropColumn('instagram');
            $table->dropColumn('donation');
            $table->dropColumn('image_1');
            $table->dropColumn('image_2');
            $table->dropColumn('color');
            $table->dropColumn('is_published');
            $table->dropColumn('slug');
        });
    }
}
