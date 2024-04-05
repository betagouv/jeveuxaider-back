<?php

namespace App\Jobs;

use App\Models\UserArchivedDatas;
use App\Services\Sendinblue;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maize\Encryptable\Encryption;

class SendinblueDeleteUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $email, public $context = null)
    {
        $this->onQueue('sendinblue');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (config('services.sendinblue.sync')) {
            Sendinblue::deleteContact($this->email);
        }

        // @todo: delete the field. Don't rely on it, as it does not work if the user has not been archived yet (async jobs from ArchiveNonActiveUsers command). Was used for the fixing script DeleteArchivedUsersFromBrevo.
        if ($this->context === 'user_archived') {
            UserArchivedDatas::where('email', Encryption::php()->encrypt($this->email))->update([
                'brevo_deleted_at' => Carbon::now()
            ]);
        }
    }
}
