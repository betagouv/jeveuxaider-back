<?php

namespace App\Console;

use App\Console\Commands\AirtableSyncOrganisationsScore;
use App\Console\Commands\AlgoliaMissionUpdateFieldOutdated;
use App\Console\Commands\ApiEngagementExportMissions;
use App\Console\Commands\ApiEngagementSyncMissions;
use App\Console\Commands\ArchiveNonActiveUsers;
use App\Console\Commands\SendNotificationsBenevoleCejNoParticipation;
use App\Console\Commands\SendNotificationsBenevoleCejOneYearAfter;
use App\Console\Commands\SendNotificationsBenevoleCejSixMonthsAfter;
use App\Console\Commands\SendNotificationsBenevoleWhenParticipationShouldBeDone;
use App\Console\Commands\SendNotificationsMissionInDraft;
use App\Console\Commands\SendNotificationsMissionOutdated;
use App\Console\Commands\SendNotificationsNoNewMission;
use App\Console\Commands\SendNotificationsReferentsSummaryDaily;
use App\Console\Commands\SendNotificationsRegisterUserVolontaireCej;
use App\Console\Commands\SendNotificationsResponsablesSummaryDaily;
use App\Console\Commands\SendNotificationsResponsablesSummaryMonthly;
use App\Console\Commands\SendNotificationsReferentsSummaryMonthly;
use App\Console\Commands\SendNotificationsStructureInDraft;
use App\Console\Commands\SendNotificationTodoToReferents;
use App\Console\Commands\SendNotificationResponsablesParticipationsNeedToBeTreated;
use App\Console\Commands\SendNotificationsStructureWithoutMission;
use App\Console\Commands\MissionsCloseOutdatedCommand;
use App\Console\Commands\SendNotificationsBenevoleFTNoParticipationJ10;
use App\Console\Commands\SendNotificationsBenevoleFTNoParticipationJ3;
use App\Console\Commands\SendNotificationsBenevoleWhenParticipationWillStart;
use App\Console\Commands\SendNotificationsMissionUserWaitingList3WeeksAfter;
use App\Console\Commands\SendNotificationsToInactiveUsers;
use App\Console\Commands\SendNotificationsUserWillBeArchived;
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



        // ONLY ENV PRODUCTION
        if (config('app.env') === 'production') {
            // $schedule->command(SendNotificationsBenevoleFTNoParticipationJ3::class)->daily()->at('09:00');
            // $schedule->command(SendNotificationsBenevoleFTNoParticipationJ10::class)->daily()->at('09:10');
            $schedule->command(SendNotificationsBenevoleWhenParticipationWillStart::class)->daily()->at('10:00');
            // $schedule->command(SendNotificationsRegisterUserVolontaireCej::class)->daily()->at('10:00'); // J+3
            // $schedule->command(SendNotificationsBenevoleCejNoParticipation::class)->daily()->at('10:10'); // J+10
            $schedule->command(SendNotificationsMissionUserWaitingList3WeeksAfter::class)->daily()->at('08:15');
            $schedule->command(SendNotificationsBenevoleCejSixMonthsAfter::class)->daily()->at('10:20');
            $schedule->command(SendNotificationsBenevoleCejOneYearAfter::class)->daily()->at('10:30');
            $schedule->command(SendNotificationsUserWillBeArchived::class)->daily()->at('11:00');
            $schedule->command(SendNotificationsToInactiveUsers::class)->daily()->at('11:30');
            $schedule->command(SendNotificationsBenevoleWhenParticipationShouldBeDone::class)->daily()->at('18:00');

            // Responsables
            $schedule->command(SendNotificationsResponsablesSummaryDaily::class)->daily()->at('07:50');
            $schedule->command(SendNotificationsResponsablesSummaryMonthly::class)->monthlyOn(1)->at('08:00');
            $schedule->command(SendNotificationsMissionOutdated::class)->daily()->at('08:30');
            $schedule->command(SendNotificationsMissionInDraft::class)->daily()->at('08:40');
            $schedule->command(SendNotificationsNoNewMission::class)->daily()->at('08:50');
            $schedule->command(SendNotificationsStructureWithoutMission::class)->daily()->at('09:10');
            $schedule->command(SendNotificationsStructureInDraft::class)->daily()->at('09:50');
            $schedule->command(SendNotificationResponsablesParticipationsNeedToBeTreated::class)->weeklyOn(1)->at('08:20');

            // Référents
            $schedule->command(SendNotificationTodoToReferents::class)->weekdays()->daily()->at('08:00');
            $schedule->command(SendNotificationsReferentsSummaryDaily::class)->days([1, 4])->at('08:10');
            $schedule->command(SendNotificationsReferentsSummaryMonthly::class)->monthlyOn(1)->at('08:20');

            // Algolia
            $schedule->command(AlgoliaMissionUpdateFieldOutdated::class)->daily()->at('02:00');

            // Sync ApiEngagement
            $schedule->command(ApiEngagementExportMissions::class)->everySixHours();
            $schedule->command(ApiEngagementSyncMissions::class)->everySixHours();

            // Sync Airtable Orga
            $schedule->command(AirtableSyncOrganisationsScore::class)->thursdays()->at('02:00');
        }

        // Horizon update dashboard metrics
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        // Purge revoked and expired tokens and auth codes
        $schedule->command('passport:purge')->hourly();

        // Clear completed batches
        $schedule->command('queue:prune-batches')->daily();

        // Close outdated missions
        $schedule->command(MissionsCloseOutdatedCommand::class)->daily()->at('09:30');

        // Archive non active users
        $schedule->command(ArchiveNonActiveUsers::class)->daily()->at('03:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
