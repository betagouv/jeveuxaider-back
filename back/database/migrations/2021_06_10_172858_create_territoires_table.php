<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerritoiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territoires', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('suffix_title');
            $table->string('slug');
            $table->text('type');
            $table->string('department')->nullable();
            $table->json('zips')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();

            $table->string('seo_recruit_title')->nullable();
            $table->text('seo_recruit_description')->nullable();
            $table->string('seo_engage_title')->nullable();
            $table->json('seo_engage_paragraphs')->nullable();

            $table->string('state')->nullable();
            $table->boolean('is_published')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('territoires');
    }
}
