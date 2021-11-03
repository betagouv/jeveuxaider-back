<?php

namespace App\Console\Commands;

use App\Models\Thematique;
use Illuminate\Console\Command;

class GenerateSlugForThematiques extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thematique:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slug for thematiques';

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
     * @return int
     */
    public function handle()
    {
        $thematiques = Thematique::get();
        $this->info($thematiques->count() . ' thematiques will be updated.');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($thematiques as $thematique) {
                $thematique->generateSlug();
                $thematique->save();
            }
        }
    }
}
