<?php

namespace App\Console\Commands;

use App\Jobs\GeolocaliseProfilesByZip as JobsGeolocaliseProfilesByZip;
use App\Models\Profile;
use App\Models\StructureTag;
use App\Services\ApiAdresse;
use Illuminate\Console\Command;

class GeolocaliseProfilesByZip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:geolocalise-profiles-by-zip {zip?*} {--limit=100}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Geolocalise profiles by zip code';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $options = $this->options();

        $zipCodes = Profile::distinct('zip')
            ->when($this->argument('zip'), function ($query, $zip) {
                return $query->whereIn('zip', $zip);
            })
            ->when(!$this->argument('zip'), function ($query, $zip) {
                return $query
                    ->whereNull('latitude')
                    ->whereNull('longitude');
            })
            ->limit($options['limit'])
            ->pluck('zip')
            ->filter();

        if ($this->confirm($zipCodes->count() . ' zips will be geolocalised. Do you wish to continue?')) {
            $zipCodes->each(function ($zip) {
               JobsGeolocaliseProfilesByZip::dispatch($zip);
            });
        }
    }
}
