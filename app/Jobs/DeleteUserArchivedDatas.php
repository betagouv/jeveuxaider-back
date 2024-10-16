<?php

namespace App\Jobs;

use App\Models\UserArchivedDatas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maize\Encryptable\Encryption;

class DeleteUserArchivedDatas implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $email)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $userArchivedDatas = UserArchivedDatas::where('email', Encryption::php()->encrypt($this->email))->first();

        if($userArchivedDatas) {
            $userArchivedDatas->delete();
        }
    }

}
