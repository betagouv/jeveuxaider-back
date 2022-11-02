<?php

namespace App\Console\Commands;

use App\Services\Sendinblue;
use Illuminate\Console\Command;

class SendinblueSyncHardBouncedAt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendinblue:sync-hard-bounced-at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users hard_bounced_at from Sendinblue';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Sync users hard_bounced_at from Sendinblue')) {
            Sendinblue::syncHardBouncedUsers();
        }
    }
}
