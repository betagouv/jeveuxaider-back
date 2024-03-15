<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Sendinblue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendinblueSyncUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $user;
    protected $oldEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $oldEmail = null)
    {
        $this->user = $user;
        $this->oldEmail = $oldEmail;
        $this->onQueue('sendinblue');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user->archived_at || $this->user->anonymous_at || $this->user->banned_at) {
            return;
        }

        if ($this->oldEmail) {
            Sendinblue::updateContactEmail($this->user, $this->oldEmail);
        } else {
            Sendinblue::sync($this->user);
        }
    }
}
