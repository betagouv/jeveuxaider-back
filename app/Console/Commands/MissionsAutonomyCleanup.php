<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class MissionsAutonomyCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:autonomy-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les valeurs de certains champs pour les missions en autonomie';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Mission::where('is_autonomy', true)
            ->where(function ($query) {
                $query->whereNotNull('address')
                    ->orWhereNotNull('zip')
                    ->orWhereNotNull('city')
                    ->orWhereNotNull('latitude')
                    ->orWhereNotNull('longitude');
            });

        if ($this->confirm("Supprimer les valeurs des champs address, zip, city, latitude, longitude des " . $query->count() . " missions en autonomie ?")) {
            $query->update([
                'address' => null,
                'zip' => null,
                'city' => null,
                'latitude' => null,
                'longitude' => null,
            ]);
        }
    }
}
