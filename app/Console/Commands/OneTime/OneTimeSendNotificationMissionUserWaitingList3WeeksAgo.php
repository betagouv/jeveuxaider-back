<?php

namespace App\Console\Commands\OneTime;

use App\Models\MissionUserWaitingList;
use App\Notifications\MissionUserWaitingListCreated3WeeksAgo;
use Illuminate\Console\Command;

class OneTimeSendNotificationMissionUserWaitingList3WeeksAgo extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'one-time:send-notification-mission-user-waiting-list-3-weeks-ago';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a notification to benevole if there are users in the waiting list 3 weeks ago';

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
        $query = MissionUserWaitingList::with(['mission','user'])
            ->whereBetween('created_at', ['2024-09-10', '2024-10-01'])
            ->whereDoesntHave('user.profile.participations', function ($query) {
                $query->whereColumn('participations.created_at', '>', 'missions_users_waiting_list.created_at');
            });

        $this->info($query->count() . ' are in waiting list since 3 weeks.');

        if ($this->confirm('Do you wish to continue ?')) {
            $query->get()->each(function ($missionUserWaitingList) {
                $mission = $missionUserWaitingList->mission;
                if($mission) {
                    $notification = new MissionUserWaitingListCreated3WeeksAgo($mission);
                    if($notification->hasSimilarMissions()) {
                        $missionUserWaitingList->user->notify($notification);
                    }
                }
            });
        }
    }
}
