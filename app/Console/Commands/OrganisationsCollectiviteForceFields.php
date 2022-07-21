<?php

namespace App\Console\Commands;

use App\Models\Domaine;
use App\Models\Structure;
use Illuminate\Console\Command;

class OrganisationsCollectiviteForceFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collectivities-force-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Force les domaines d'actions et les publics bénéficiaires pour les organisations de type Collectivité";

    protected $domaines;

    protected $publics;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->domaines = Domaine::all();
        $this->publics = config('taxonomies.mission_publics_beneficiaires.terms');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Structure::withTrashed()->where('statut_juridique', 'Collectivité');
        $this->info('Ce script va forcer les champs domaines et publics bénéficiaires pour les '.$query->count().' organisations de type Collectivité');

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $collectivite) {
                $this->forceDomaines($collectivite);
                $this->forcePublicsBeneficiaires($collectivite);
                $collectivite->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function forceDomaines($collectivite)
    {
        $values = $this->domaines->pluck($this->domaines, 'id')->map(function ($item) {
            return ['field' => 'structure_domaines'];
        });
        $collectivite->domaines()->sync($values);
    }

    private function forcePublicsBeneficiaires($collectivite)
    {
        $collectivite->publics_beneficiaires = array_keys($this->publics);
    }
}
