<?php

namespace App\Console\Commands;

use App\Models\MissionUserWaitingList;
use App\Notifications\MissionUserWaitingListCreated3WeeksAgo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotificationsMissionUserWaitingList3WeeksAfter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-mission-user-waiting-list-3-weeks-after';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to enevoles in waiting lists 3 weeks after.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = MissionUserWaitingList::with(['mission','user'])
            ->whereBetween('created_at', [
                Carbon::now()->subWeeks(3)->startOfDay(),
                Carbon::now()->subWeeks(3)->endOfDay(),
            ])
            ->whereDoesntHave('user.profile.participations', function ($query) {
                $query->whereColumn('participations.created_at', '>', 'missions_users_waiting_list.created_at');
            });

        foreach ($query->get() as $missionUserWaitingList) {
            $notification = new MissionUserWaitingListCreated3WeeksAgo($missionUserWaitingList->mission);
            if($notification->hasSimilarMissions()) {
                $missionUserWaitingList->user->notify(new MissionUserWaitingListCreated3WeeksAgo($missionUserWaitingList->mission));
            }
        }
    }
}
