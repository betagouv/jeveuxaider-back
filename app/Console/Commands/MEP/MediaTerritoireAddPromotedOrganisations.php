<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use App\Models\Structure;
use App\Models\Territoire;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MediaTerritoireAddPromotedOrganisations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:media-territoire-add-promoted-organisations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Add Promoted Organisations for Territoire";

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
        $this->info("!!! NE LANCER LE SCRIPT QU'UNE SEULE FOIS !!!");
        $this->info('Ce script va ajouter les logos des associations partenaires aux territoires');

        if ($this->confirm('Continuer ?')) {
            $this->addPromotedOrganisations();
        }
    }

    private function addPromotedOrganisations()
    {
        $query = DB::table('territoire_relations');
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();

        foreach ($query->cursor() as $relation) {
            $territoire = Territoire::find($relation->territoire_id);
            $structure = Structure::find($relation->relation_id);
            $logo = Media::where('collection_name', "structure__logo")
                ->where('model_id', $relation->relation_id)->first();
            if ($logo) {
                try {
                    $mediaTerritoire = $logo->copy($territoire, 'territoire__promoted_organisations');
                    $mediaTerritoire->name = $structure->name;
                    $mediaTerritoire->saveQuietly();
                } catch (\Throwable $th) {
                    $this->warn('Media # ' . $logo->id . ' : File not found (' . $logo->getFullUrl() . '). Skipped.');
                }
            }

            $bar->advance();
        }
        $bar->finish();
    }
}
