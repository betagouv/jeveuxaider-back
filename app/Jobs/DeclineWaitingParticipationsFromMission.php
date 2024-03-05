<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Notifications\ParticipationDeclinedFromMissionTerminated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeclineWaitingParticipationsFromMission implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Mission $mission, public string $reason = 'mission_terminated')
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

                $participation->load('profile', 'conversation');

                activity()
                    ->performedOn($participation)
                    ->withProperties([
                        'attributes' => ['state' => 'Refusée'],
                        'old' => ['state' => $participation->state]
                    ])
                    ->event('updated - auto closed')
                    ->log('updated');

                $participation->state = 'Refusée';
                $participation->saveQuietly();

                $participation->conversation->messages()->create([
                    'type' => 'contextual',
                    'content' => 'La participation a été refusée',
                    'contextual_state' => 'Refusée',
                    'contextual_reason' => $reason,
                ]);

                if($reason === 'mission_terminated') {
                    $participation->profile->notify(new ParticipationDeclinedFromMissionTerminated($participation));
                }

            });

        $mission->structure->calculateScore();
    }
}
