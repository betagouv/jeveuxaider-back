<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\Tag;
use Illuminate\Console\Command;

class FillStructurePublics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-structure-publics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill structure publics if empty';

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
        $globalQuery = Structure::whereJsonLength('publics_beneficiaires', '=', 0)->whereHas('missions');

        $this->info($globalQuery->count() . ' structures will be updated with publics from their missions');

        if ($this->confirm('Do you wish to continue?')) {
            $structures = $globalQuery->get();

            foreach ($structures as $structure) {
                $publics = [];
                $missions = Mission::where('structure_id', $structure->id)->get();

                foreach ($missions as $mission) {
                    $publics = array_merge($mission->publics_beneficiaires, $publics);
                }

                if (!empty($publics)) {
                    $structure->publics_beneficiaires = array_values(array_unique($publics));
                    $structure->saveQuietly();
                }
            }
        }
    }
}
