<?php

namespace App\Jobs;

use App\Models\Participation;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParticipationDeclineWhenUserIsBanned implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $participation;
    protected $reason;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($participationId, $reason)
    {
        $this->participation = Participation::find($participationId);
        $this->reason = $reason;
        $this->onQueue('high-tasks');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        if ($this->participation->conversation) {
            $this->participation->conversation->messages()->create([
                'type' => 'contextual',
                'content' => 'La participation a été automatiquement déclinée par la plateforme',
                'contextual_state' => 'Automatiquement déclinée par la plateforme',
                'contextual_reason' => $this->reason,
            ]);
        }

        $oldParticipationState = $this->participation->state;
        $this->participation->state = 'Refusée';
        $this->participation->saveQuietly();

        if (in_array($oldParticipationState, ['En attente de validation', 'En cours de traitement'])) {
            $this->participation->mission->structure->calculateScore();
        }

        // Places left & Algolia
        if ($this->participation->mission) {
            $this->participation->mission->update();
        }

        activity()
            ->performedOn($this->participation)
            ->withProperties([
                'attributes' => ['state' => 'Refusée'],
                'old' => ['state' => $oldParticipationState]
            ])
            ->event('updated')
            ->log('updated');
    }
}
