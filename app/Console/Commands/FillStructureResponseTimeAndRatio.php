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
            ->where('id', 4309)
            ->get();

        $this->info($structures->count() . ' structures will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($structures as $structure) {
                // Response time
                $participationsIds = $structure->participations->pluck('id')->all();
                $structure->response_time = intval(Conversation::whereIn('conversable_id', $participationsIds)->avg('response_time'));

                // Response ratio
                $participationsCount = $structure->participations->count();

                if ($participationsCount) {
                    $participationsWaitingCount = $structure->participations->where('state', 'En attente de validation')->count();
                    $structure->response_ratio = round(($participationsCount - $participationsWaitingCount) / $participationsCount * 100);
                }

                $structure->saveQuietly(); // No observer
            }
        }
    }
}
