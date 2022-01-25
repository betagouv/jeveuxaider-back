<?php

namespace App\Console\Commands\OLD;

use App\Models\Mission;
use Illuminate\Console\Command;

class AssignResponsableToMission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:assign-responsable-to-mission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign responsable to mission';

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
        $query = Mission::withTrashed()->whereNull('responsable_id');
        $this->info($query->count() . ' missions will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $missions = $query->get();
            foreach ($missions as $mission) {
                if ($mission->structure) {
                    $newResponsableProfileId = $mission->structure->members->pluck('id')->first();
                    if ($newResponsableProfileId) {
                        $mission->update(['responsable_id' => $newResponsableProfileId]);
                    }
                }
            }
        }
    }
}
