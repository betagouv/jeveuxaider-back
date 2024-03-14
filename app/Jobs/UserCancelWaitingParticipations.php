<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserCancelWaitingParticipations implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public string $reason = 'user_archived')
    {
        $this->onQueue('low-tasks');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
        $reason = $this->reason;

        $user->profile?->participations()->with(['conversation'])->whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->each(function ($participation) use ($user, $reason) {
                $participation->conversation?->messages()->create([
                    'from_id' => $user->id,
                    'type' => 'contextual',
                    'content' => 'La participation a été annulée',
                    'contextual_state' => 'Annulée',
                    'contextual_reason' => $reason,
                ]);

                activity()
                    ->performedOn($participation)
                    ->withProperties([
                        'attributes' => ['state' => 'Annulée'],
                        'old' => ['state' => $participation->state]
                    ])
                    ->event('updated')
                    ->log('updated');

                $participation->state = 'Annulée';
                $participation->saveQuietly();
            });
    }
}
