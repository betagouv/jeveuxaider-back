<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;

class GenerateStructuresResponseRatioAndTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structures:set-response-ratio-and-time {structureIds?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate structures response_ratio and response_time';

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

        $query = Structure::whereNotNull('response_time');
        $structureIds = $this->argument('structureIds');
        if (!empty($structureIds)) {
            $query->whereIn('id', $structureIds);
        }

        $this->info($query->count() . ' structures will be updated with new response_time and response ratio');

        if ($this->confirm('Do you wish to continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $structure) {
                $structure->setResponseRatio();
                $structure->setResponseTime();
                $structure->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}
