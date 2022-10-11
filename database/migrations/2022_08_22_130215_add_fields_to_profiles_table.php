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
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('cej')->default(false);
            $table->timestamp('cej_updated_at')->nullable();
            $table->string('cej_email_adviser')->nullable();
            $table->date('service_civique_completion_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('cej');
            $table->dropColumn('cej_updated_at');
            $table->dropColumn('cej_email_adviser');
            $table->dropColumn('service_civique_completion_date');
        });
    }
};
