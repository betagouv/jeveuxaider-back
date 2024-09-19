<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Notifications\MissionUserWaitingListNoPlaceLeftCreated;
use App\Notifications\MissionUserWaitingListRegistrationClosedCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyResponsablesMissionUserWaitingListCreated implements ShouldQueue
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
        if($this->mission->responsables()->doesntExist()) {
            return;
        }

        $this->mission->responsables->each(function ($responsable) {
            if($responsable->notification__responsable_frequency == 'realtime') {
                if(!$this->mission->is_registration_open) {
                    $responsable->notify(new MissionUserWaitingListRegistrationClosedCreated($this->mission));
                } elseif (!$this->mission->has_places_left) {
                    $responsable->notify(new MissionUserWaitingListNoPlaceLeftCreated($this->mission));
                }
            }
        });

    }
}
