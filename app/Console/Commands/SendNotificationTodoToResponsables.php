<?php

namespace App\Console\Commands;

use App\Models\Participation;
use App\Models\Profile;
use App\Notifications\ResponsableDailyTodo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;

class SendNotificationTodoToResponsables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:responsables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Responsables when new content';

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
        $nb_jours_notif = 10;

        $participationsByResponsable = Participation::with(['mission.responsables'])->where('state', 'En attente de validation')
          ->where('created_at', '>', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->whereHas('mission.responsables', function (Builder $query) {
            $query->where('profiles.notification__responsable_frequency', 'realtime');
          })
          ->get()
          ->groupBy('mission.responsables.id');

        foreach ($participationsByResponsable as $responsableId => $participations) {
            if (! $responsableId) {
                return; // Hack car des missions n'ont pas de responsables
            }
            Notification::send(Profile::find($responsableId), new ResponsableDailyTodo($participations));
        }
    }
}
