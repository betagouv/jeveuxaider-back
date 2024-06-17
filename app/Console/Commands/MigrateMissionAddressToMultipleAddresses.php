<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateMissionAddressToMultipleAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-multiple-addresses {--limit=1}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate multiple addresses from autonomy_zips to addresses field in missions table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $options = $this->options();

        $queryCount = DB::table('missions')->whereNull('addresses')->where('type', 'Mission en présentiel')->count();

        if ($this->confirm("{$queryCount} addresses will be migrated")) {

            DB::table('missions')->whereNull('addresses')->where('type', 'Mission en présentiel')->limit($options['limit'])->get()->each(function ($mission) {

                $addresses = [];

                $zips = json_decode($mission->autonomy_zips_old, true);

                if(isset($zips) && !empty($zips)) {
                    foreach ($zips as $zip) {
                        $addresses[] = [
                            "id" => random_int(100000, 999999),
                            "label" => $zip['zip'] . ' ' . $zip['city'] ?? null,
                            "street" => null,
                            "zip" => $zip['zip'] ?? null,
                            "city" => $zip['city'] ?? null,
                            "department" => $mission->department,
                            "latitude" => $zip['latitude'] ?? null,
                            "longitude" => $zip['longitude'] ?? null,
                        ];
                    }
                } else {
                    $addresses[] = [
                        "id" => random_int(100000, 999999),
                        "label" => ($mission->address_old == $mission->city_old) ? "{$mission->zip_old} {$mission->city_old}" : "{$mission->address_old} {$mission->zip_old} {$mission->city_old}",
                        "street" => $mission->address_old ?? null,
                        "zip" => $mission->zip_old ?? null,
                        "city" => $mission->city_old ?? null,
                        "department" => $mission->department,
                        "latitude" => $mission->latitude_old ?? null,
                        "longitude" => $mission->longitude_old ?? null,
                    ];
                }

                DB::table('missions')
                    ->where('id', $mission->id)
                    ->update(['addresses' => $addresses]);

            });
        }
    }
}
