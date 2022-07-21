<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Media;
use Illuminate\Console\Command;

class MediaDomaineIlustrationsOrganisations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:media-domaine-add-illustrations-organisations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Media domaine - Ajoute les illustrations pour les organisations rattachées';

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
        $this->info('Ajout des illustrations pour les organisations rattachées aux modèles de type Domaine');

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $domaine) {
                $this->addMissionIllustrations($domaine);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function addMissionIllustrations($domaine)
    {
        foreach ($this->getIllustrationsData($domaine) as $illustration) {
            $count = Media::where('collection_name', 'domaine__illustrations_organisation')
                ->where('name', $illustration['filename'])
                ->count();

            if (empty($count)) {
                $domaine->addMedia($illustration['url'])
                    ->usingFileName($illustration['filename'])
                    ->usingName($illustration['filename'])
                    ->preservingOriginal()
                    ->withCustomProperties(['old_thumbnail' => $illustration['old_thumbnail']])
                    ->toMediaCollection('domaine__illustrations_organisation');
            }
        }
    }

    private function getIllustrationsData($domaine)
    {
        $data = [];
        $folder = 'app/Console/Commands/MEP/medias_domaine/organisations/';
        switch ($domaine->slug) {
            case 'art-et-culture-pour-tous':
                $id = 11;
                $count = 5;
                break;
            case 'cooperation-internationale':
                $id = 10;
                $count = 5;
                break;
            case 'memoire-et-citoyennete':
                $id = 9;
                $count = 5;
                break;
            case 'sante-pour-tous':
                $id = 3;
                $count = 5;
                break;
            case 'solidarite-et-insertion':
                $id = 6;
                $count = 5;
                break;
            case 'sport-pour-tous':
                $id = 7;
                $count = 5;
                break;
            case 'education-pour-tous':
                $id = 2;
                $count = 5;
                break;
            case 'prevention-et-protection':
                $id = 8;
                $count = 5;
                break;
            case 'protection-de-la-nature':
                $id = 4;
                $count = 5;
                break;
            case 'mobilisation-covid-19':
                $id = 1;
                $count = 5;
                break;

            default:
                $count = 0;
                break;
        }

        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'filename' => $domaine->slug.'-'.$i.'.webp',
                'url' => base_path().'/'.$folder.$id.'_'.$i.'@2x.jpg',
                'old_thumbnail' => $id.'_'.$i,
            ];
        }

        return $data;
    }
}
