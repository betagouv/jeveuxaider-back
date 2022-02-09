<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\MissionTemplate;
use App\Models\Tag;
use App\Models\Term;
use App\Models\Thematique;
use App\Models\Vocabulary;
use Illuminate\Console\Command;

class CreateVocabularies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:create-vocabularies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create vocabularies";

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
        // TAGS
        $tags = Vocabulary::create(['name' => 'Tags']);

        // SKILLS
        $skills = Vocabulary::create(['name' => 'Skills']);
        $skillsTags = Tag::where('type', 'competence')->orderBy('name->fr')->get();

        foreach ($skillsTags as $weight => $tag) {
            Term::create([
                'vocabulary_id' => $skills->id,
                'name' => $tag->name,
                'weight' => $weight
            ]);
        }
    }
}
