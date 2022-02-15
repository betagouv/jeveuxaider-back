<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Media;
use Illuminate\Console\Command;

class MediaDomaineOrganisationsPartenairesActives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:media-domaine-add-organisations-partenaires-actives';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Media domaine - Ajoute les logos des organisations partenaires actives";

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
        $this->info("Ajout des logos des organisations partenaires actifs aux modèles de type Domaine");

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $domaine) {
                $this->addLogosPartenairesActifs($domaine);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function addLogosPartenairesActifs($domaine)
    {
        foreach ($this->getLogosData($domaine) as $illustration) {
            $count = Media::where('collection_name', 'domaine__logos_partenaires_actifs')
                ->where('name', $illustration['filename'])
                ->count();

            if (empty($count)) {
                $domaine->addMedia($illustration['url'])
                    ->preservingOriginal()
                    ->toMediaCollection('domaine__logos_partenaires_actifs');
            }
        }
    }

    private function getLogosData($domaine)
    {
        $data = [];
        $folder = 'app/Console/Commands/MEP/medias_domaine/active_partners/';
        switch ($domaine->slug) {
            case 'art-culture-pour-tous':
            case 'cooperation-internationale':
            case 'memoire-et-citoyennete':
            case 'sante-pour-tous':
            case 'solidarite-et-insertion':
            case 'sport-pour-tous':
                $count = 5;
                break;

            case 'education-pour-tous':
            case 'prevention-et-protection':
                $count = 4;
                break;

            case 'protection-de-la-nature':
                $count = 3;
                break;

            default:
                $count = 0;
                break;
        }

        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'filename' => $domaine->slug . '-' . $i,
                'url' => base_path() . '/' . $folder . $domaine->slug . '-active-' . $i . '.jpg',
            ];
        }

        return $data;
    }
}
