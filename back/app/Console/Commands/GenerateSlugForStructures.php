<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;

class GenerateSlugForStructures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structure:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slug for structures';

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
        $structures = Structure::whereNull('slug')->withTrashed()->get();
        $this->info($structures->count() . ' structures will be updated.');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($structures as $structure) {
                $structure->generateSlug();
                $structure->saveQuietly();
            }
        }
    }
}
