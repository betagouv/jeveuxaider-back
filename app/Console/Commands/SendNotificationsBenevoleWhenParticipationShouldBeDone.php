<?php

namespace App\Console\Commands;

use App\Models\Participation;
use App\Notifications\ParticipationShouldBeDone;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendNotificationsBenevoleWhenParticipationShouldBeDone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-participation-should-be-done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to volunteer when their participation should have already taken place.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendReminderForPonctualMissions();
        $this->sendReminderForRecursiveMissions();
    }

    private function sendReminderForPonctualMissions()
    {
        $query = Participation::whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->whereHas('mission', function (Builder $query) {
                $query->where('date_type', 'ponctual')
                ->whereBetween('end_date', [
                    Carbon::now()->subDays(1)->startOfDay(),
                    Carbon::now()->subDays(1)->endOfDay(),
                ]);
            });

        foreach ($query->get() as $participation) {
            Notification::send($participation->profile, new ParticipationShouldBeDone($participation));
        }
    }

    private function sendReminderForRecursiveMissions()
    {
        $query = Participation::whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->whereBetween('created_at', [
                Carbon::now()->subMonths(2)->startOfDay(),
                Carbon::now()->subMonths(2)->endOfDay(),
            ])
            ->whereHas('mission', function (Builder $query) {
                $query->where('date_type', 'recurring')
                    ->orWhereNull('end_date');
            });

        foreach ($query->get() as $participation) {
            Notification::send($participation->profile, new ParticipationShouldBeDone($participation));
        }
    }
}
