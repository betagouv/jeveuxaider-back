<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\MissionTemplate;
use App\Models\Thematique;
use Illuminate\Console\Command;

class GenerateDomainesFromThematiques extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:generate-domaines-from-thematiques';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate domaines from thematiques";

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
        $thematiques = Thematique::with('domaine')->get();
        $this->info($thematiques->count() . ' domaines will be generated');
        if ($this->confirm('Do you wish to continue?')) {
            $thematiques->map(function ($thematique) {
                Domaine::create([
                    'name' => $thematique->domaine->name,
                    'title' => $thematique->title,
                    'published' => $thematique->published,
                    'description' => $thematique->description,
                ]);
            });
        }
    }
}
