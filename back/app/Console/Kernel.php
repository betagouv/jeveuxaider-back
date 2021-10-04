<?php

namespace App\Console;

use App\Console\Commands\SendNotificationTodoToModerateurs;
use App\Console\Commands\SendNotificationTodoToReferents;
use App\Console\Commands\SendNotificationTodoToResponsables;
use App\Console\Commands\SyncApiEngagement;
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

        $schedule->command(SendNotificationTodoToModerateurs::class)->weekdays()->daily()->at('08:00');
        $schedule->command(SendNotificationTodoToReferents::class)->weekdays()->daily()->at('08:10');
        $schedule->command(SendNotificationTodoToResponsables::class)->days([1, 3, 5])->at('08:20');
        $schedule->command(SendNotificationsMissionOutdated::class)->weekdays()->daily()->at('08:30');

        // Sync ApiEngagement
        $schedule->command(SyncApiEngagement::class)->everySixHours();

        // Horizon update dashboard metrics
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->command('sitemap:generate')->daily()->at('07:00');

        // Purge revoked and expired tokens and auth codes
        $schedule->command('passport:purge')->hourly();
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
