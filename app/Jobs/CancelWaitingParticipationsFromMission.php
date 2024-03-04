<?php

namespace App\Jobs;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelWaitingParticipationsFromMission implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Mission $mission, public string $reason = 'mission_canceled')
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mission = $this->mission;
        $reason = $this->reason;

        $mission->participations->whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->each(function ($participation) use ($reason) {
                $participation->state = 'Annulée';
                $participation->saveQuietly();

                $participation->conversation->messages()->create([
                    'type' => 'contextual',
                    'content' => 'La participation a été annulée',
                    'contextual_state' => 'Annulée',
                    'contextual_reason' => $reason,
                ]);

                if($reason === 'mission_signaled') {
                    //
                }
                if($reason === 'mission_canceled') {
                    //
                }

            });
    }
}
