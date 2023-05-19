<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class CancelParticipationsFromCancelledMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel-participations-from-cancelled-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel Participations From Cancelled Missions';

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
        $participations = Participation::whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->whereHas('mission', function(Builder $query){
                $query->where('state', 'Annulée');
            });

        $count = $participations->count();

        if ($this->confirm($count.' participation(s) will be cancelled')) {
            $participations->update(['state' => 'Annulée']);
            $this->info($count.' participation(s) has been canceled. No notification has been sent.');
        }
    }
}
