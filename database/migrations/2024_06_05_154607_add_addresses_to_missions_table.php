<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->json('addresses')->nullable();
        });

        // Update the existing missions withfrom autonomy_zips field

        // DB::table('missions')->whereNotNull('autonomy_addresses')->update([
        //     'addresses' => DB::raw(`JSON_ARRAY(
        //         JSON_OBJECT(
        //             "zip", [value of the autonomy_addresses->zip],
        //             "city", [value of the autonomy_addresses->city],
        //             "contexte", $this->getContexteFromZip([value of the autonomy_addresses->zip]),
        //         )
        //     )`),
        // ]);

        // DB::table('missions')->get()->each(function ($mission) {

        //     $addresses = [];

        //     $zips = json_decode($mission->autonomy_zips, true);

        //     if(isset($zips) && !empty($zips)) {
        //         foreach ($zips as $zip) {
        //             $addresses[] = [
        //                 "id" => random_int(100000, 999999),
        //                 "label" => $zip['zip'] . ' ' . $zip['city'] ?? null,
        //                 "street" => null,
        //                 "zip" => $zip['zip'] ?? null,
        //                 "city" => $zip['city'] ?? null,
        //                 "department" => $mission->department,
        //                 "latitude" => $zip['latitude'] ?? null,
        //                 "longitude" => $zip['longitude'] ?? null,
        //             ];
        //         }
        //     } else {
        //         $addresses[] = [
        //             "id" => random_int(100000, 999999),
        //             "label" => $mission->full_address ?? null,
        //             "street" => $mission->address ?? null,
        //             "zip" => $mission->zip ?? null,
        //             "city" => $mission->city ?? null,
        //             "department" => $mission->department,
        //             "latitude" => $mission->latitude ?? null,
        //             "longitude" => $mission->longitude ?? null,
        //         ];
        //     }

        //     DB::table('missions')
        //         ->where('id', $mission->id)
        //         ->update(['addresses' => $addresses]);

        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropColumn('addresses');
        });
    }
};
