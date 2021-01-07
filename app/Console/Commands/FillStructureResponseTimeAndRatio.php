<?php

namespace App\Console\Commands;

use App\Models\Conversation;
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
        $structures = Structure::whereNull('response_ratio')
            ->orWhereNull('response_time')
            ->limit(10)
            ->get();

        $this->info($structures->count() . ' structures will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($structures as $structure) {
                $this->info("Processing structure #{$structure->id} {$structure->name}");

                // Response time
                $participationsIds = $structure->participations->pluck('id')->all();
                $responseTime = intval(Conversation::whereIn('conversable_id', $participationsIds)->avg('response_time'));
                $this->info("Response time: {$responseTime}");
                $structure->response_time = $responseTime;

                // Response ratio
                $participationsCount = $structure->participations->count();

                if ($participationsCount) {
                    $participationsWaitingCount = $structure->participations->where('state', 'En attente de validation')->count();
                    $responseRatio = round(($participationsCount - $participationsWaitingCount) / $participationsCount * 100);
                    $structure->response_ratio = $responseRatio;
                    $this->info("Response ratio: {$responseRatio}");
                } else {
                    $this->info("Response ratio: no participation");
                }

                $structure->saveQuietly(); // No observer
            }
        }
    }
}
