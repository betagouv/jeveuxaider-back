<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;

class FillStructureResponseRatio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-structure-response-ratio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill structure response ratio if null';

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
        $globalQuery = Structure::whereHas('participations')->whereNull('response_ratio');

        $this->info($globalQuery->count() . ' structures will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $structures = $globalQuery->get();
            foreach ($structures as $structure) {
                $structure->setResponseRatio()->saveQuietly();
                $this->info("Saving structure #{$structure->id} {$structure->name} response ratio to {$structure->response_ratio}");
            }
        }
    }
}
