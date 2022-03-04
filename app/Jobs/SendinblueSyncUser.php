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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->onQueue('sendinblue');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Sendinblue::sync($this->user);
    }
}
