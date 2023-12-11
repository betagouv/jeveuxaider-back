<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserUpdateLastInteractionAt implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->onQueue('webhooks');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->payload || !isset($this->payload['email'])) {
            return;
        }

        if (!isset($this->payload['date'])) {
            $this->payload['date'] = now();
        }

        $user = User::where('email', $this->payload['email'])->first();

        if (empty($user)) {
            return;
        }

        $user->last_interaction_at = $this->payload['date'];
        $user->saveQuietly();
    }
}
