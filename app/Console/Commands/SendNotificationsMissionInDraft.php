<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Notifications\MissionStillInDraft;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsMissionInDraft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:mission-in-draft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Responsables when their mission is in draft since 7 days.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Mission::with(['responsable'])->where('state', 'Brouillon')
        ->whereBetween('created_at', [
            Carbon::now()->subDays(7)->startOfDay(),
            Carbon::now()->subDays(7)->endOfDay(),
        ]);

        foreach ($query->get() as $mission) {
            Notification::send($mission->responsable, new MissionStillInDraft($mission));
        }
    }
}
