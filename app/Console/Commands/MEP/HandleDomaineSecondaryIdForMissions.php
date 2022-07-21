<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Mission;
use App\Models\Taggable;
use Illuminate\Console\Command;

class HandleDomaineSecondaryIdForMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:handle-domaine-secondary-id-for-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle domaine_secondary_id For Missions';

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
        $this->info('Table missions, colonne domaine_secondary_id : ce script va écraser la relation vers un tag et la remplacer par une liaison avec un domaine');

        if ($this->confirm('Continuer ?')) {
            $this->handleMissionSecondaryDomain();
        }
    }

    private function handleMissionSecondaryDomain()
    {
        $this->info('Table mission : domaine_secondary_id');

        $query = Taggable::where('taggable_type', "App\Models\Mission")
            ->where('tag_id', '<=', 11);

        $bar = $this->output->createProgressBar($query->count());
        $bar->start();

        foreach ($query->cursor() as $taggable) {
            $domaine = Domaine::where('name', 'ILIKE', $taggable->tag->name)->first();
            $mission = Mission::find($taggable->taggable_id);
            if ($mission && ! $mission->domaine_secondary_id) {
                $mission->domaine_secondary_id = $domaine->id;
                $mission->saveQuietly();
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
