<?php

namespace App\Console\Commands\MEP;

use App\Models\Taggable;
use App\Models\Term;
use App\Models\Termable;
use Illuminate\Console\Command;

class GenerateTermablesSkills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:generate-termables-skills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate termables skills";

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
        $query = Taggable::whereHas('tag', function ($query) {
            $query->where('type', 'competence');
        })->orderBy('tag_id');

        $this->info($query->count() . ' skills will be linked');

        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $taggable) {
                $term = Term::where('name', $taggable->tag->name)->first();
                if ($term) {
                    Termable::create([
                        'term_id' => $term->id,
                        'termable_id' => $taggable->taggable_id,
                        'termable_type' => $taggable->taggable_type,
                        'field' => 'profile_skills',
                    ]);
                    // $this->info($taggable->tag_id . '-' . $taggable->tag->name . ' term created.');
                } else {
                    $this->warn($taggable->tag_id . '-' . $taggable->tag->name . 'not found. Skipped.');
                }
                $bar->advance();
            }
            $bar->finish();
        }
    }
}
