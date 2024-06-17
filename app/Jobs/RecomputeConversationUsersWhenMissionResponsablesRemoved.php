<?php

namespace App\Jobs;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecomputeConversationUsersWhenMissionResponsablesRemoved implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Mission $mission, public array $removedResponsableUserIds)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->mission->participations->each(function ($participation) {
            $participation->loadMissing('conversation');
            $participation->conversation->users()->detach($this->removedResponsableUserIds);
        });
    }
}
