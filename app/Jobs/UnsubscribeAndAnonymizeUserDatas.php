<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnsubscribeAndAnonymizeUserDatas implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        $this->onQueue('high-tasks');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = $this->user->id . '@anonymized.fr';

        $this->user->anonymous_at = Carbon::now();
        $this->user->name = $email;
        $this->user->email = $email;
        $this->user->profile->email = $email;
        $this->user->profile->first_name = 'Utilisateur';
        $this->user->profile->last_name = 'Désinscrit';
        $this->user->profile->phone = null;
        $this->user->profile->mobile = null;
        $this->user->profile->birthday = null;

        $this->user->saveQuietly();
        $this->user->profile->saveQuietly();
    }
}
