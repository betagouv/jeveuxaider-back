<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Media;
use Illuminate\Console\Command;

class MediaDomaineIllustrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:media-domaine-add-illustrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Media domaine - Ajoute les illustrations";

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
        $this->info("Ajout des illustrations secondaires aux modèles de type Domaine");

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $domaine) {
                $this->addSecondaryIllustrations($domaine);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function addSecondaryIllustrations($domaine)
    {
        foreach ($this->getIllustrationsData($domaine) as $illustration) {
            $count = Media::where('collection_name', 'domaine__illustrations')
                ->where('name', $illustration['filename'])
                ->count();

            if (empty($count)) {
                $domaine->addMedia($illustration['url'])
                    ->preservingOriginal()
                    ->toMediaCollection('domaine__illustrations');
            }
        }
    }

    private function getIllustrationsData($domaine)
    {
        $data = [];
        $folder = 'app/Console/Commands/MEP/medias_domaine/secondary_illustrations/';
        switch ($domaine->slug) {
            case 'sante-pour-tous':
                $count = 6;
                break;

            case 'solidarite-et-insertion':
                $count = 6;
                break;

            case 'education-pour-tous':
                $count = 6;
                break;

            case 'protection-de-la-nature':
                $count = 6;
                break;

            default:
                $count = 0;
                break;
        }

        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'filename' => $domaine->slug . '-' . $i,
                'url' => base_path() . '/' . $folder . $domaine->slug . '-' . $i . '.jpg',
            ];
        }

        return $data;
    }
}
