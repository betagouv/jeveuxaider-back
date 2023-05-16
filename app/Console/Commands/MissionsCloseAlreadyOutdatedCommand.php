<?php

namespace App\Console\Commands;

use App\Jobs\MissionCloseAlreadyOutdatedJob;
use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class MissionsCloseAlreadyOutdatedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:close-already-outdated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One shot, do not execute more than once. Close missions and send testimony notifications.';

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
        $queryMission = Mission::with(['responsable'])->whereIn('id', $this->getmissionIds())
            ->where('state', 'Validée')
            ->where('end_date', '<', Carbon::now());
        $missionIds = $queryMission->pluck('id');

        $queryParticipationsBeingRefused = Participation::whereIn('mission_id', $missionIds)
            ->whereIn('state', ['En attente de validation', 'En cours de traitement']);

        $queryParticipationsValidatedFromMissionsOutdatedForLessThan6Months = Participation::whereIn('mission_id', $missionIds)
            ->where('state', 'Validée')
            ->whereHas('mission', function (Builder $query) {
                $query->where('end_date', '>', Carbon::now()->subMonth(6));
            });

        $this->info("{$missionIds->count()} missions vont être terminées.");
        $this->info("{$queryParticipationsBeingRefused->count()} participations en attente et en cours de traitement seront refusées (sans envoi de notification).");
        $this->info("{$queryParticipationsValidatedFromMissionsOutdatedForLessThan6Months->count()} notifications de témoignage seront envoyées.");

        if ($this->confirm("Continuer ?")) {
            $missionIds->each(fn ($id) => MissionCloseAlreadyOutdatedJob::dispatch($id));
        }
    }

    private function getmissionIds()
    {
        // @todo: Extract des ids à la MEP
        return [];
    }
}
