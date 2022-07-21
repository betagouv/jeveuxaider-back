<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Console\Command;

class MissionValidateParticipations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:validate-participations {mission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate participations of a mission
                                {mission : The ID of the mission}';

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

        $participationsQuery = Participation::where('mission_id', $id);

        $count = $participationsQuery->count();
        if ($this->confirm($count.' participation(s) will be validated for '.$mission->name.'(Organization: '.$mission->structure->name.')')) {
            $participationsQuery->update(['state' => 'Validée']); // Without observer
            $mission->save(); // Calculate places left

            $this->info($count.' participation(s) has been validated. No notification has been sent.');
        }
    }
}
