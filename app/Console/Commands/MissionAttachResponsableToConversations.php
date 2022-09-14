<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class MissionAttachResponsableToConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:attach-responsable-to-conversations {missionIds*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enforces that the responsable of the mission is part of the participations conversations';

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
        $missionIds = $this->argument('missionIds');
        $queryMissions = Mission::whereIn('id', $missionIds);

        if ($this->confirm("Enforcer l'accès aux conversations des responsables de ces ".$queryMissions->count().' missions ?')) {
            $missions = $queryMissions->get();
            foreach ($missions as $mission) {
                $participations = $mission->participations;
                foreach ($participations as $participation) {
                    $participation->conversation->users()->syncWithoutDetaching([$mission->responsable->user->id]);
                }
            }
        }
    }
}
