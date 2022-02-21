<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use App\Models\Territoire;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HandlePromotedOrganisationsForTerritoire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:handle-promoted-organisations-for-territoire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Handle Promoted Organisations for Territoire";

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
        $this->info('Ce script va ajouter les logos des associations partenaires aux territoires');

        if ($this->confirm('Continuer ?')) {
            $this->handlePromotedOrganisations();
        }
    }

    private function handlePromotedOrganisations()
    {
        $query = DB::table('territoire_relations');
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();

        foreach ($query->cursor() as $relation) {
            // Seulement si pas déjà existant
            if (Media::where('model_type', 'App\Models\Territoire')->where('model_id', $relation->territoire_id)->where('collection_name', 'territoire__promoted_organisations')->count()) {
                continue;
            }

            $territoire = Territoire::find($relation->territoire_id);
            $logo = Media::where('collection_name', "structure__logo")
                ->where('model_id', $relation->relation_id)->first();
            if ($logo) {
                $mediaTerritoire = $logo->copy($territoire, 'territoire__promoted_organisations');
                $mediaTerritoire->saveQuietly();
            }

            $bar->advance();
        }
        $bar->finish();
    }
}
