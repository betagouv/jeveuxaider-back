<?php

namespace App\Jobs;

use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecomputeConversationUsersWhenMissionResponsablesAdded implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Mission $mission, public array $addedResponsableUserIds)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $newResponsablesIds = collect($this->addedResponsableUserIds)->mapWithKeys(function ($id) {
            return [$id => ['read_at' => Carbon::now()]];
        });

        $this->mission->participations->each(function ($participation) use ($newResponsablesIds) {
            $participation->loadMissing('conversation');

            $participation->conversation->users()->syncWithoutDetaching($newResponsablesIds);
        });
    }
}
