<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\BenevoleIsInactive;
use App\Notifications\BenevoleIsInactiveSecondReminder;
use App\Notifications\ResponsableIsInactive;
use App\Notifications\ResponsableIsInactiveSecondReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsToInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:inactive-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to inactive users (M+3 and M+6)';

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

    // 3 mois après la dernière interaction.
    private function handleFirstNotification()
    {
        $query = User::canReceiveNotifications()
            ->whereBetween('last_interaction_at', [
                Carbon::now()->subMonths(3)->startOfDay(),
                Carbon::now()->subMonths(3)->endOfDay(),
            ]);

        $query->cursor()->each(function (User $user) {
            $user->loadMissing('roles');
            if ($user->hasRole('responsable')) {
                $user->loadMissing('structures');
                $structure = $user->structures->first();
                if ($structure && !in_array($structure->state, ['Désinscrite', 'Signalée'])) {
                    Notification::send($user, new ResponsableIsInactive($structure));
                } else {
                    Notification::send($user, new BenevoleIsInactive());
                }
            } else {
                Notification::send($user, new BenevoleIsInactive());
            }
        });
    }

    // 6 mois après la dernière interaction.
    private function handleSecondNotification()
    {
        $query = User::canReceiveNotifications()
            ->whereBetween('last_interaction_at', [
                Carbon::now()->subMonths(6)->startOfDay(),
                Carbon::now()->subMonths(6)->endOfDay(),
            ]);

        $query->cursor()->each(function (User $user) {
            $user->loadMissing('roles');
            if ($user->hasRole('responsable')) {
                $user->loadMissing('structures');
                $structure = $user->structures->first();
                if ($structure && !in_array($structure->state, ['Désinscrite', 'Signalée'])) {
                    Notification::send($user, new ResponsableIsInactiveSecondReminder($structure));
                } else {
                    Notification::send($user, new BenevoleIsInactiveSecondReminder());
                }
            } else {
                Notification::send($user, new BenevoleIsInactiveSecondReminder());
            }
        });
    }
}
