<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Notifications\MissionOutdated;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class SendNotificationsMissionOutdated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:mission-outdated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Responsables when their mission is outdated since 15 days.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Mission::with(['responsable'])->where('state', 'Validée')
        ->whereBetween('end_date', [
            Carbon::now()->subDays(15)->startOfDay(),
            Carbon::now()->subDays(15)->endOfDay()
        ]);

        foreach ($query->get() as $mission) {
            Notification::send($mission->responsable, new MissionOutdated($mission));
        }
    }
}
