<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Models\User;
use App\Notifications\MissionHasAvailablePlace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyMissionUserWaitingList implements ShouldQueue
{
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    public $mission;

    public $filePath;

    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
    }

    public function handle()
    {

        $this->mission->usersInWaitingList->each(function (User $user) {
            $user->notify(new MissionHasAvailablePlace($this->mission));
        });

        DeleteMissionUserWaitingList::dispatch($this->mission);
    }
}
