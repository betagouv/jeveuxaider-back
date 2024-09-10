<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Models\MissionUserWaitingList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteMissionUserWaitingList implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Mission $mission)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MissionUserWaitingList::where('mission_id', $this->mission->id)->delete();
    }
}
