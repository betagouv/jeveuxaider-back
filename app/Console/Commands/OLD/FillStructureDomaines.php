<?php

namespace App\Console\Commands\OLD;

use App\Models\Mission;
use App\Models\Structure;
use App\Models\Tag;
use Illuminate\Console\Command;

class FillStructureDomaines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-structure-domaines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill structure domaines if empty';

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
        $globalQuery = Structure::whereDoesntHave('tags')->whereHas('missions');

        $this->info($globalQuery->count().' structures will be updated with domaines from their missions');

        if ($this->confirm('Do you wish to continue?')) {
            $structures = $globalQuery->get();

            foreach ($structures as $structure) {
                $domaineIds = [];
                $missions = Mission::where('structure_id', $structure->id)->get();

                foreach ($missions as $mission) {
                    $mainDomain = $mission->domaines->first();
                    if ($mainDomain && ! in_array($mainDomain->id, $domaineIds)) {
                        $domaineIds[] = $mainDomain->id;
                    }
                }

                if ($domaineIds) {
                    $domaines = Tag::whereIn('id', $domaineIds)->get();
                    $structure->syncTagsWithType($domaines, 'domaine');
                }
            }
        }
    }
}
