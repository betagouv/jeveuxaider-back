<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Sendinblue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendinblueDeleteUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        $this->onQueue('sendinblue');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (config('services.sendinblue.sync')) {
            Sendinblue::deleteContact($this->user);
        }
    }
}
