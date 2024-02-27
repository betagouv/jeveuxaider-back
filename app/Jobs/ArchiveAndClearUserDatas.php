<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ArchiveAndClearUserDatas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!env('ENCRYPTION_KEY') || empty(env('ENCRYPTION_KEY'))) {
            throw new \Exception('ENCRYPTION_KEY not found in .env file');
        }

        if ($this->user->archivedDatas) {
            throw new \Exception('Les données ne peuvent pas être archivées pour ' . $this->user->id);
        }

        $payload = [
            'email' => $this->user->email,
            'first_name' => $this->user->profile->first_name,
            'last_name' => $this->user->profile->last_name,
            'birthday' => $this->user->profile->birthday,
            'phone' => $this->user->profile->phone,
            'mobile' => $this->user->profile->mobile,
        ];

        $this->user->archivedDatas()->create([
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'datas' => serialize($payload),
            'code' => random_int(100000, 999999),
        ]);

        $this->clearUserAttributes();
    }

    private function clearUserAttributes(): void
    {
        $email = $this->user->id . '@archived.fr';

        $this->user->archived_at = Carbon::now();
        $this->user->name = $email;
        $this->user->email = $email;
        $this->user->profile->email = $email;
        $this->user->profile->first_name = 'Utilisateur';
        $this->user->profile->last_name = 'Archivé';
        $this->user->profile->phone = null;
        $this->user->profile->mobile = null;
        $this->user->profile->birthday = null;

        $this->user->saveQuietly();
        $this->user->profile->saveQuietly();
    }
}
