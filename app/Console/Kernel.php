<?php

namespace App\Console;

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
        // Backups
        //$schedule->command('backup:clean')->daily()->at('05:00');
        //$schedule->command('backup:run')->daily()->at('05:00')->withoutOverlapping();

        // Check Security updates
        $schedule->command(\Jorijn\LaravelSecurityChecker\Console\SecurityMailCommand::class)->daily()->at('05:05');
        $schedule->command(\Jorijn\LaravelSecurityChecker\Console\SecuritySlackCommand::class)->daily()->at('05:10');

        // Todo
        //schedule notif referent, daily lun-vend,at8am


        // Horizon update dashboard metrics
        //  $schedule->command('horizon:snapshot')->everyFiveMinutes();
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
