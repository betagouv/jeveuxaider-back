<?php

namespace App\Console;

use App\Console\Commands\ApiEngagementExportMissions;
use App\Console\Commands\ApiEngagementSyncMissions;
use App\Console\Commands\SendNotificationsMissionInDraft;
use App\Console\Commands\SendNotificationsMissionOutdated;
use App\Console\Commands\SendNotificationsNoNewMission;
use App\Console\Commands\SendNotificationsStructureInDraft;
use App\Console\Commands\SendNotificationTodoToModerateurs;
use App\Console\Commands\SendNotificationTodoToReferents;
use App\Console\Commands\SendNotificationTodoToResponsables;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Check Security updates
        //$schedule->command(\Jorijn\LaravelSecurityChecker\Console\SecurityMailCommand::class)->daily()->at('05:05');
        //$schedule->command(\Jorijn\LaravelSecurityChecker\Console\SecuritySlackCommand::class)->daily()->at('05:10');

        // $schedule->command(SendNotificationTodoToModerateurs::class)->weekdays()->daily()->at('08:00');
        $schedule->command(SendNotificationTodoToReferents::class)->weekdays()->daily()->at('08:10');
        $schedule->command(SendNotificationTodoToResponsables::class)->days([1, 3, 5])->at('08:20');
        $schedule->command(SendNotificationsMissionOutdated::class)->weekdays()->daily()->at('08:30');
        $schedule->command(SendNotificationsMissionInDraft::class)->weekdays()->daily()->at('08:40');
        $schedule->command(SendNotificationsNoNewMission::class)->weekdays()->daily()->at('08:50');
        $schedule->command(SendNotificationsStructureInDraft::class)->daily()->at('09:50');

        // Sync ApiEngagement
        $schedule->command(ApiEngagementExportMissions::class)->everySixHours();
        $schedule->command(ApiEngagementSyncMissions::class)->everySixHours();

        // Horizon update dashboard metrics
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        // Purge revoked and expired tokens and auth codes
        $schedule->command('passport:purge')->hourly();

        // Clear completed batches
        $schedule->command('queue:prune-batches')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
