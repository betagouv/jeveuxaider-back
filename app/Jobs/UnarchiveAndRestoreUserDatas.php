<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Maize\Encryptable\Encryption;

class UnarchiveAndRestoreUserDatas implements ShouldQueue
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

        if (!$this->user->archivedDatas) {
            throw new \Exception('Les données ne sont pas archivées pour ' . $this->user->id);
        }

        $this->user->archived_at = null;

        $datas = Encryption::php()->decrypt($this->user->archivedDatas->datas);
        $payload = unserialize($datas);

        $this->user->anonymous_at = null;
        $this->user->name =  $payload['email'];
        $this->user->email = $payload['email'];
        $this->user->profile->email = $payload['email'];
        $this->user->profile->first_name = $payload['first_name'];
        $this->user->profile->last_name = $payload['last_name'];
        $this->user->profile->phone = $payload['phone'];
        $this->user->profile->mobile = $payload['mobile'];
        $this->user->profile->birthday = $payload['birthday'];

        $this->user->saveQuietly();
        $this->user->profile->saveQuietly();

        $this->user->archivedDatas()->delete();
    }

}
