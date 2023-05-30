<?php

namespace App\Console\Commands;

use App\Jobs\StructureCalculateScore;
use App\Models\Structure;
use Illuminate\Console\Command;

class StructuresComputeScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structures:compute-score {structureIds?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute structures scores';

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
        $query = Structure::query();
        $structureIds = $this->argument('structureIds');
        if (!empty($structureIds)) {
            $query->whereIn('id', $structureIds);
        }

        $this->info($query->count() . ' structures will have their score recomputed');

        if ($this->confirm('Do you wish to continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $structure) {
                StructureCalculateScore::dispatch($structure);
                $bar->advance();
            }

            $bar->finish();
        }
    }
}
