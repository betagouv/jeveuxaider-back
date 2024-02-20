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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;

        $user->profile->participations()->with(['conversation'])->whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->each(function ($participation) use ($user) {
                $participation->conversation->messages()->create([
                    'from_id' => $user->id,
                    'type' => 'contextual',
                    'content' => 'La participation a été annulée',
                    'contextual_state' => 'Désinscription',
                    'contextual_reason' => 'user_unsubscribed',
                ]);

                activity()
                    ->causedBy($user)
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
