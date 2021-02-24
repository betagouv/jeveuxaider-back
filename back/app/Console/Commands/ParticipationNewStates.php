<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;

class ParticipationNewStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participation:new-states';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New states for participation';

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
        $participationsValidated = Participation::where('state', 'Mission validée');
        $count = $participationsValidated->count();
        if ($this->confirm('Mission validée -> Validée : '. $count)) {
            $participationsValidated->update(['state' => 'Validée']);
            $this->info($count . ' participations updated.');
        }

        $participationsRefused = Participation::where('state', 'Participation déclinée');
        $count = $participationsRefused->count();
        if ($this->confirm('Participation déclinée -> Refusée : '. $count)) {
            $participationsRefused->update(['state' => 'Refusée']);
            $this->info($count . ' participations updated.');
        }

        $participationsDone = Participation::where('state', 'Mission effectuée');
        $count = $participationsDone->count();
        if ($this->confirm('Mission effectuée -> Effectuée : '. $count)) {
            $participationsDone->update(['state' => 'Effectuée']);
            $this->info($count . ' participations updated.');
        }

        $participationsCanceled = Participation::whereIn('state', ['Candidature annulée', 'Mission refusée', 'Mission signalée', 'Mission annulée', 'Mission abandonnée']);
        $count = $participationsCanceled->count();
        if ($this->confirm('Candidature annulée, Mission annulée, Mission abandonnée -> Annulée : '. $count)) {
            $participationsCanceled->update(['state' => 'Annulée']);
            $this->info($count . ' participations updated.');
        }
    }
}
