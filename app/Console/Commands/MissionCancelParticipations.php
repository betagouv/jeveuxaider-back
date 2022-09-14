<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Console\Command;

class MissionCancelParticipations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:cancel-participations {mission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel waiting participation of a mission {mission : The ID of the mission}';

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
        $id = $this->argument('mission');
        $mission = Mission::find($id);

        if (! $mission) {
            $this->error('This mission doesnt exists!');

            return;
        }

        $participations = Participation::whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->where('mission_id', $id);

        $count = $participations->count();
        if ($this->confirm($count.' participation(s) will be cancelled for '.$mission->name.'(Organization: '.$mission->structure->name.')')) {
            $participations->update(['state' => 'Annulée']);
            $mission->save();
            $this->info($count.' participation(s) has been canceled. No notification has been sent.');
        }
    }
}
