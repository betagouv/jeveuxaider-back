<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\BenevoleWillBeArchived;
use App\Notifications\BenevoleWillBeArchivedSecondReminder;
use App\Notifications\ResponsableWillBeArchived;
use App\Notifications\ResponsableWillBeArchivedSecondReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsUserWillBeArchived extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:user-will-be-archived';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to users that will be archived (M-1 and D-7)';

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
        $this->handleFirstNotification();
        $this->handleSecondNotification();
    }

    // 1 mois avant l'archivage.
    private function handleFirstNotification()
    {
        $query = User::canReceiveNotifications()
            ->whereBetween('last_interaction_at', [
                Carbon::now()->subYears(3)->addMonth()->startOfDay(),
                Carbon::now()->subYears(3)->addMonth()->endOfDay(),
            ]);

        $query->cursor()->each(function (User $user) {
            $user->loadMissing('roles');
            if ($user->hasRole('responsable')) {
                $user->loadMissing('structures');
                $structure = $user->structures->first();
                if ($structure) {
                    Notification::send($user, new ResponsableWillBeArchived($structure));
                }
            } else {
                Notification::send($user, new BenevoleWillBeArchived());
            }
        });
    }

    // 7 jours avant l'archivage.
    private function handleSecondNotification()
    {
        $query = User::canReceiveNotifications()
            ->whereBetween('last_interaction_at', [
                Carbon::now()->subYears(3)->addDays(7)->startOfDay(),
                Carbon::now()->subYears(3)->addDays(7)->endOfDay(),
            ]);

        $query->cursor()->each(function (User $user) {
            $user->loadMissing('roles');
            if ($user->hasRole('responsable')) {
                $user->loadMissing('structures');
                $structure = $user->structures->first();
                if ($structure) {
                    Notification::send($user, new ResponsableWillBeArchivedSecondReminder($structure));
                }
            } else {
                Notification::send($user, new BenevoleWillBeArchivedSecondReminder());
            }
        });
    }
}
