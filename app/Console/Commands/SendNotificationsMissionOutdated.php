<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Notifications\MissionOutdatedFirstReminder;
use App\Notifications\MissionOutdatedSecondReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
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
    protected $description = 'Send Notifications to Responsables when their mission is outdated since 5 days. Second reminder at 20 days.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendFirstReminder();
        $this->sendSecondReminder();
    }

    private function sendFirstReminder()
    {
        $query = Mission::with(['responsables'])->where('state', 'Validée')
            ->whereBetween('end_date', [
                Carbon::now()->subDays(5)->startOfDay(),
                Carbon::now()->subDays(5)->endOfDay(),
            ]);

        foreach ($query->get() as $mission) {
            $mission->responsables->each(function ($responsable) use ($mission) {
                Notification::send($responsable, new MissionOutdatedFirstReminder($mission));
            });
        }
    }

    private function sendSecondReminder()
    {
        $query = Mission::with(['responsables'])->where('state', 'Validée')
            ->whereBetween('end_date', [
                Carbon::now()->subDays(20)->startOfDay(),
                Carbon::now()->subDays(20)->endOfDay(),
            ]);

        foreach ($query->get() as $mission) {
            $mission->responsables->each(function ($responsable) use ($mission) {
                Notification::send($responsable, new MissionOutdatedSecondReminder($mission));
            });
        }
    }
}
