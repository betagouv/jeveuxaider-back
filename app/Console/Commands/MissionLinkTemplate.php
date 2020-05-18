<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\MissionTemplate;
use Illuminate\Console\Command;

class MissionLinkTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:link-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link mission to an existing template';

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
        $domaines = config('taxonomies.mission_domaines.terms');

        foreach ($domaines as $key => $domaine) {
            $template = $this->getTemplate($key);
            if ($template) {
                Mission::where('name', $key)->update([
                    'template_id' => $template->id,
                ]);
            } else {
                $this->error('No template found for key: ' . $key);
            }
        }
    }

    private function getTemplate($key)
    {
        switch ($key) {
            case "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis":
                $id = 1;
                break;
            case "Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance":
                $id = 3;
                break;
            case "Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)":
                $id = 4;
                break;
            case "Je fais les courses de produits essentiels pour mes voisins les plus fragiles.":
                $id = 2;
                break;
            case "soutien_scolaire_a_distance":
                $id = 6;
                break;
            case "fabrication_distribution_equipements":
                $id = 5;
                break;
        }

        return isset($id) ? MissionTemplate::find($id) : null;
    }
}
