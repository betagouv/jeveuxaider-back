<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('structures_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('structure_id')->index()->nullable();
            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_generic')->default(false);
            $table->timestamps();
        });

        Schema::create('structures_taggables', function (Blueprint $table) {
            $table->unsignedBigInteger('structure_tag_id');
            $table->morphs('taggable');
            $table->unique(['structure_tag_id', 'taggable_id', 'taggable_type']);
            $table->foreign('structure_tag_id')->references('id')->on('structures_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures_taggables');
        Schema::dropIfExists('structures_tags');
    }
};
