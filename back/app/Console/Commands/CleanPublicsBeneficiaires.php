<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class CleanPublicsBeneficiaires extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:clean-publics-beneficiaires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "S'assure que les keys et non les labels sont sauvés en base.";

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
        $query = Mission::withTrashed();
        $this->info($query->count() . ' missions will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $mission) {
                $cleanValues = array_map(function ($value) {
                    switch ($value) {
                        case 'Personnes âgées':
                            $value = "seniors";
                            break;

                        case 'Personnes en situation de handicap':
                            $value = "persons_with_disabilities";
                            break;

                        case 'Personnes à la rue':
                        case 'Personnes en difficulté':
                            $value = "people_in_difficulty";
                            break;

                        case 'Parents':
                            $value = "parents";
                            break;

                        case 'Jeunes / enfants':
                        case 'jeunes_enfants':
                            $value = "children";
                            break;

                        case 'Tous publics':
                            $value = "any_public";
                            break;

                        default:
                            break;
                    }
                    return $value;
                }, $mission->publics_beneficiaires);
                $mission->publics_beneficiaires = array_unique($cleanValues);

                $mission->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}
