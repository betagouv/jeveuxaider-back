<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;

class ParticipationsValidateAccesLibre extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participations-validate-acceslibre';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Valide les participations en cours de traitement de l'organisation Acceslibre";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Participation::ofStructure(10692)
            ->where('state', 'En cours de traitement');


        if ($this->confirm("Valider les participations en cours de traitement de l'organisation Acceslibre ? " . $query->count() . ' participations vont être mises à jour.')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $participation) {
                $participation->state = 'Validée';
                $participation->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}
