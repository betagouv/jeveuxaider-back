<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
use Illuminate\Console\Command;

class MigrateOrganisationMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-organisation-missions {origin_id} {destination_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate mission from an organisation
                                {origin_id : The ID of the organisation origin}
                                {destination_id : The ID of the organisation destination}';

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
        $structureOrigin = Structure::find($this->argument('origin_id'));
        $structureDestination = Structure::find($this->argument('destination_id'));

        if (!$structureOrigin) {
            $this->error("This organisation {$this->argument('origin_id')} doesnt exists!");
            return;
        }

        if (!$structureDestination) {
            $this->error("This organisation {$this->argument('destination_id')} doesnt exists!");
            return;
        }

        $responsable = $structureDestination->members->first();

        if (!$responsable) {
            $this->error("This organisation {$this->argument('destination_id')} doesnt have any responsable");
            return;
        }

        $missionsQuery = Mission::where('structure_id', $structureOrigin->id)->withTrashed();

        $count = $missionsQuery->count();
        if ($this->confirm("{$count} missions(s) will be migrated from {$structureOrigin->name} to {$structureDestination->name} and will be linked to {$responsable->full_name} {$responsable->email}")) {
            $missionsQuery->update([
                'structure_id' => $structureDestination->id,
                'responsable_id' => $responsable->id
            ]);
            $this->info($count . ' missions(s) has been migrated.');
        }
    }
}