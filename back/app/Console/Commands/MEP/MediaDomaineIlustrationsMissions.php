<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Media;
use Illuminate\Console\Command;

class MediaDomaineIlustrationsMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:media-domaine-add-illustrations-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Media domaine - Ajoute les illustrations pour les missions rattachées";

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
        $this->info("Ajout des illustrations pour les missions rattachées aux modèles de type Domaine");

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
            $count = Media::where('collection_name', 'domaine__illustrations_mission')
                ->where('name', $illustration['filename'])
                ->count();

            if (empty($count)) {
                $domaine->addMedia($illustration['url'])
                    ->usingFileName($illustration['filename'])
                    ->usingName($illustration['filename'])
                    ->preservingOriginal()
                    ->toMediaCollection('domaine__illustrations_mission');
            }
        }
    }

    private function getIllustrationsData($domaine)
    {
        $data = [];
        $folder = 'app/Console/Commands/MEP/medias_domaine/missions/';
        switch ($domaine->slug) {
            case 'art-culture-pour-tous':
                $id = 11;
                $count = 9;
                break;
            case 'cooperation-internationale':
                $id = 10;
                $count = 3;
                break;
            case 'memoire-et-citoyennete':
                $id = 9;
                $count = 3;
                break;
            case 'sante-pour-tous':
                $id = 3;
                $count = 9;
                break;
            case 'solidarite-et-insertion':
                $id = 6;
                $count = 9;
                break;
            case 'sport-pour-tous':
                $id = 7;
                $count = 9;
                break;
            case 'education-pour-tous':
                $id = 2;
                $count = 9;
                break;
            case 'prevention-et-protection':
                $id = 8;
                $count = 9;
                break;
            case 'protection-de-la-nature':
                $id = 4;
                $count = 9;
                break;
            case 'mobilisation-covid-19':
                $id = 1;
                $count = 3;
                break;

            default:
                $count = 0;
                break;
        }

        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'filename' => $domaine->slug . '-' . $i . '.webp',
                'url' => base_path() . '/' . $folder . $id . '_' . $i . '@2x.webp',
            ];
        }

        return $data;
    }
}
