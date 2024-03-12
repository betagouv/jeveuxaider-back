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

class DeclineParticipation implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $participation;
    protected $currentUserId;
    protected $reason;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($participationId, $currentUserId, $reason, $message)
    {
        $this->participation = Participation::find($participationId);
        $this->currentUserId = $currentUserId;
        $this->reason = $reason;
        $this->message = $message;
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
            // Determine if the batch has been cancelled...
            return;
        }

        $user->declineParticipation($this->participation, $this->reason, $this->message);
    }
}
