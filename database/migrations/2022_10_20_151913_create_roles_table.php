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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('name')->unique();
            $table->bigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
        });

        Schema::rename('members', 'old_members');

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('is_admin', 'old_is_admin');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('referent_department', 'old_referent_department');
            $table->renameColumn('referent_region', 'old_referent_region');
            $table->renameColumn('tete_de_reseau_id', 'old_tete_de_reseau_id');
            $table->dropColumn('is_analyste');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('rolables', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->nullableMorphs('rolable');
            $table->string('fonction')->nullable();
            $table->unique(['role_id', 'user_id', 'rolable_type', 'rolable_id']);
        });

        Schema::rename('profile_territoire', 'old_profile_territoire');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('old_members', 'members');

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('old_is_admin', 'is_admin');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('old_referent_department', 'referent_department');
            $table->renameColumn('old_referent_region', 'referent_region');
            $table->renameColumn('old_tete_de_reseau_id', 'tete_de_reseau_id');
            $table->boolean('is_analyste')->default(false);
        });
        Schema::dropIfExists('rolables');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('regions');

        Schema::rename('old_profile_territoire', 'profile_territoire');
    }
};
