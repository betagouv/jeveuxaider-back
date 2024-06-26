<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('missions_responsables', function (Blueprint $table) {
            $table->integer('mission_id')->unsigned()->index();
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');
            $table->integer('responsable_id')->unsigned()->index();
            $table->foreign('responsable_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->index(['mission_id', 'responsable_id']);
            $table->unique(['mission_id', 'responsable_id']);
        });

        // Insert current responsable_id from missions table into missions_responsables table
        DB::table('missions_responsables')->insertUsing(
            ['mission_id', 'responsable_id'],
            DB::table('missions')->select(['id', 'responsable_id'])->whereNotNull('responsable_id')
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions_responsables');
    }
};
