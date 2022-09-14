<?php

namespace App\Console\Commands\MEP;

use App\Models\Mission;
use Illuminate\Console\Command;

class FillMissionsSnuFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:fill-missions-snu-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill missions snu fields';

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
     * @return int
     */
    public function handle()
    {
        $missionsSnu = Mission::whereJsonContains('publics_volontaires', 'Jeunes volontaires du Service National Universel');
        $this->info($missionsSnu->count().' missions will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($missionsSnu->count());
            $bar->start();

            foreach ($missionsSnu->cursor() as $mission) {
                $mission->publics_volontaires = collect($mission->publics_volontaires)->filter(function ($item) {
                    return $item != 'Jeunes volontaires du Service National Universel';
                })->values();

                if ($mission->type == 'Mission en présentiel') {
                    $mission->is_snu_mig_compatible = true;
                    $mission->snu_mig_places = $mission->places_left;
                }

                $mission->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}
