<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Media;
use Illuminate\Console\Command;

class MediaDomaineOrganisationsPartenaires extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:media-domaine-add-organisations-partenaires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Media domaine - Ajoute les logos des organisations partenaires";

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
        $query = Domaine::query();
        $this->info("Ajout des logos des organisations partenaires aux modèles de type Domaine");

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $domaine) {
                $this->addLogosPartenaires($domaine);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function addLogosPartenaires($domaine)
    {
        foreach ($this->getLogosData($domaine) as $illustration) {
            $count = Media::where('collection_name', 'domaine__logos_partenaires')
                ->where('name', $illustration['filename'])
                ->count();

            if (empty($count)) {
                $domaine->addMedia($illustration['url'])
                    ->preservingOriginal()
                    ->toMediaCollection('domaine__logos_partenaires');
            }
        }
    }

    private function getLogosData($domaine)
    {
        $data = [];
        $folder = 'app/Console/Commands/MEP/medias_domaine/partners/';
        switch ($domaine->slug) {
            case 'art-culture-pour-tous':
            case 'education-pour-tous':
            case 'protection-de-la-nature':
            case 'sante-pour-tous':
                $count = 2;
                break;

            case 'cooperation-internationale':
            case 'memoire-et-citoyennete':
            case 'prevention-et-protection':
            case 'solidarite-et-insertion':
            case 'sport-pour-tous':
                $count = 3;
                break;

            default:
                $count = 0;
                break;
        }

        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'filename' => $domaine->slug . '-' . $i,
                'url' => base_path() . '/' . $folder . $domaine->slug . '-partenaire-' . $i . '.jpg',
            ];
        }

        return $data;
    }
}
