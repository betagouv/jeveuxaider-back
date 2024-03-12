<?php

namespace App\Jobs;

use App\Models\Participation;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Passport\Passport;

class ValidateParticipation implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $participation;

    protected $currentUserId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($participationId, $currentUserId)
    {
        $this->participation = Participation::find($participationId);
        $this->currentUserId = $currentUserId;
        $this->onQueue('high-tasks');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->currentUserId);
        Passport::actingAs($user);

        if ($this->batch()->cancelled()) {
            return;
        }

        $oldParticipationState = $this->participation->state;
        $this->participation->update(['state' => 'Validée']);
        if (in_array($oldParticipationState, ['En attente de validation', 'En cours de traitement'])) {
            $this->participation->mission->structure->calculateScore();
        }

        // Places left & Algolia
        if ($this->participation->mission) {
            $this->participation->mission->update();
        }
    }
}
