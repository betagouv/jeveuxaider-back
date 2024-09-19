<?php

namespace App\Console\Commands\OneTime;

use App\Jobs\NotifyResponsablesMissionUserWaitingListCreated;
use App\Models\Mission;
use Illuminate\Console\Command;

class NotifyResponsableMissionUserWaitingList extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'one-time:notify-responsable-mission-user-waiting-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a notification to the responsable of the mission if there are users in the waiting list';

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
        $query = Mission::whereHas('usersInWaitingList');

        $this->info($query->count() . ' missions have users in the waiting list');

        if ($this->confirm('Do you wish to continue ?')) {
            $query->get()->each(function ($mission) {
                NotifyResponsablesMissionUserWaitingListCreated::dispatch($mission);
            });
        }
    }
}
