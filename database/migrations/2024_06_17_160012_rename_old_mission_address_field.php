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
        Schema::table('missions', function (Blueprint $table) {
            $table->renameColumn('zip', 'zip_old');
            $table->renameColumn('city', 'city_old');
            $table->renameColumn('address', 'address_old');
            $table->renameColumn('latitude', 'latitude_old');
            $table->renameColumn('longitude', 'longitude_old');
            $table->renameColumn('is_autonomy', 'is_autonomy_old');
            $table->renameColumn('autonomy_zips', 'autonomy_zips_old');
            $table->renameColumn('autonomy_precisions', 'autonomy_precisions_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->renameColumn('zip_old', 'zip');
            $table->renameColumn('city_old', 'city');
            $table->renameColumn('address_old', 'address');
            $table->renameColumn('latitude_old', 'latitude');
            $table->renameColumn('longitude_old', 'longitude');
            $table->renameColumn('is_autonomy_old', 'is_autonomy');
            $table->renameColumn('autonomy_zips_old', 'autonomy_zips');
            $table->renameColumn('autonomy_precisions_old', 'autonomy_precisions');
        });
    }
};
