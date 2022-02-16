<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domainables', function (Blueprint $table) {
            $table->foreignId('domaine_id')->constrained('domaines')->onDelete('cascade');
            $table->morphs('domainable');
            $table->string('field');

            $table->unique(['domaine_id', 'domainable_id', 'domainable_type', 'field']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domainables');
    }
}
