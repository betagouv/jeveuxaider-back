<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class EndAssessorMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:end-assessor-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ends all assessor missions';

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
        $queryMissions = Mission::where('template_id', 112)
            ->where('state', '<>', 'Terminée');

        if ($this->confirm($queryMissions->count() . ' missions vont être mises à jour.')) {
            $missions = (clone $queryMissions)->get();

            // Without triggering observers, no notification sent.
            $queryMissions->update(['state' => 'Terminée']);

            foreach ($missions as $mission) {
                $queryValidatedParticipations = $mission->participations()->where('state', 'Validée');
                $queryPendingParticipations = $mission->participations()->where('state', 'En attente de validation');

                // Without triggering observers, no notification sent.
                $queryValidatedParticipations->update(['state' => 'Effectuée']);
                $queryPendingParticipations->update(['state' => 'Annulée']);

                // With observers, notifications are sent.
                // foreach ($queryValidatedParticipations->get() as $participation) {
                //     $participation->update(['state' => 'Effectuée']);
                // }
                // foreach ($queryPendingParticipations->get() as $participation) {
                //     $participation->update(['state' => 'Annulée']);
                // }
            }
        }
    }
}
