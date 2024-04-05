<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserSetHardBouncedAt implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $email;
    protected $timestamp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $timestamp)
    {
        $this->email = $email;
        $this->timestamp = $timestamp;
        $this->onQueue('webhooks');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where('email', $this->email)->first();
        if ($user) {
            // Don't rely on it (as in keep sending email to hardbouced users), as there is no Brevo hook when a contact has been "de-hardbounced"
            // The field just serves to log hardbounced users, to be able to potentially contact them by other means (sms)
            $user->hard_bounced_at = $this->timestamp;
            $user->saveQuietly();
        }
    }
}
