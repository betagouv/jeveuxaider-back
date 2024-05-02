<?php

namespace App\Console\Commands;

use App\Jobs\SendinblueSyncUser;
use App\Models\User;
use Illuminate\Console\Command;

class SendinblueSyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendinblue:sync-users {--fromId=} {--toId=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users in Sendinblue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();
        if (empty($options['fromId'])) {
            $this->error('Mandatory option: --fromId');
            return;
        }
        if (empty($options['toId'])) {
            $this->error('Mandatory option: --toId');
            return;
        }
        if (config('services.sendinblue.sync') !== true) {
            $this->error('Sendinblue synchronisation is disabled !');
            return;
        }

        $query = User::canReceiveNotifications()
            ->where('id', '>=', $options['fromId'])
            ->where('id', '<=', $options['toId'])
            ->orderBy('id');

        if ($this->confirm($query->count() . ' users will be added or updated in Sendinblue')) {
            $start = now();
            $executionTime = 0;
            $query->cursor()->each(function (User $user) use (&$start, &$executionTime) {
                SendinblueSyncUser::dispatch($user);

                $time = $start->diffInSeconds(now());
                if ($executionTime !== $time && ($time - $executionTime) % 5 === 0) {
                    $this->comment("Processing... ($time seconds)");
                    $executionTime = $time;
                }
            });
        }
    }
}
