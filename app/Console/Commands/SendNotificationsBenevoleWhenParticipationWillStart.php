<?php

namespace App\Console\Commands;

use App\Models\Participation;
use App\Notifications\ParticipationWillStart;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendNotificationsBenevoleWhenParticipationWillStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-participation-will-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to volunteer when their participation will start.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Participation::with(['profile'])
            ->whereIn('state', ['Validée'])
            ->whereHas('mission', function (Builder $query) {
                $query->where('date_type', 'ponctual')
                ->where('state', 'Validée')
                ->whereBetween('start_date', [
                    Carbon::tomorrow()->startOfDay(),
                    Carbon::tomorrow()->endOfDay(),
                ])
                ->whereHas('structure', function (Builder $query) {
                    $query->where('state', 'Validée');
                });
            });

        foreach ($query->get() as $participation) {
            Notification::send($participation->profile, new ParticipationWillStart($participation));
        }
    }

}
