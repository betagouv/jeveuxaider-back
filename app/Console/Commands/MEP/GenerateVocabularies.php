<?php

namespace App\Console\Commands\MEP;

use App\Models\Tag;
use App\Models\Term;
use App\Models\Vocabulary;
use Illuminate\Console\Command;

class GenerateVocabularies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:generate-vocabularies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate vocabularies';

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
        if (! Vocabulary::where('name', 'Tags')->count()) {
            Vocabulary::create(['name' => 'Tags']);
            $this->info('Tags has been created !');
        } else {
            $this->warn('Tags already exists. Aborting.');
        }

        if (! Vocabulary::where('name', 'Skills')->count()) {
            $skills = Vocabulary::create(['name' => 'Skills']);
            $skillsTags = Tag::where('type', 'competence')->orderBy('name->fr')->get();
            foreach ($skillsTags as $weight => $tag) {
                Term::create([
                    'vocabulary_id' => $skills->id,
                    'name' => $tag->name,
                    'weight' => $weight,
                    'properties' => [
                        'group' => $tag->group,
                    ],
                ]);
            }
            $this->info($skillsTags->count().' skills has been created !');
        } else {
            $this->warn('Skills already exists. Aborting.');
        }
    }
}
