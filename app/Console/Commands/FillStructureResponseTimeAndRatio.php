<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;

class FillStructureResponseTimeAndRatio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-structure-response-time-and-ratio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill structure response time and ratio if null';

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
        $structures = Structure::whereHas('participations')->whereNull('response_ratio')
            ->orWhereNull('response_time')
            ->get();

        $this->info($structures->count() . ' structures will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($structures as $structure) {
                $this->info("Processing structure #{$structure->id} {$structure->name}");
                $participationsCount = $structure->participations->count();
                if ($participationsCount) {
                    // RESPONSE TIME
                    $avgResponseTime = $structure->conversations->avg('response_time');
                    if ($avgResponseTime) {
                        $structure->response_time = intval($avgResponseTime);
                    }

                    // RESPONSE RATIO
                    $conversationsWithResponseTimeCount = $structure->conversations->whereNotNull('response_time')->count();
                    $structure->response_ratio =  round($conversationsWithResponseTimeCount / $participationsCount * 100);
                    $structure->saveQuietly();
                }
            }
        }
    }
}
