<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Sendinblue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserSetHardBouncedAt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            $user->hard_bounced_at = $this->timestamp;
            $user->saveQuietly();
        }
    }
}
