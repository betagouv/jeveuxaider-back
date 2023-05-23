<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\ResponsableParticipationAModeredEnPriorite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class SendNotificationResponsablesParticipationsNeedToBeTreated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:responsables-participations-need-to-be-treated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to responsables for participations which need to be treated in priority';

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
        // @TODO -> 1 fois par mois max ?
        $results = DB::select(
            "
                SELECT missions.responsable_id, COUNT(*) As total_count,
                COUNT(*) filter(WHERE participations.state = 'En attente de validation' AND participations.created_at < (NOW() - INTERVAL '10 days')) As waiting_count,
                COUNT(*) filter(WHERE participations.state = 'En cours de traitement' AND participations.created_at < (NOW() - INTERVAL '2 months')) As in_progress_count
                FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN profiles ON profiles.id = missions.responsable_id
                WHERE profiles.notification__responsable_frequency = 'realtime'
                AND (
                    (participations.state = 'En attente de validation' AND participations.created_at < (NOW() - INTERVAL '10 days'))
                    OR (participations.state = 'En cours de traitement' AND participations.created_at < (NOW() - INTERVAL '2 months'))
                )
                AND missions.responsable_id IS NOT NULL
                GROUP BY missions.responsable_id
                ORDER BY total_count DESC
                LIMIT 5
            "
        );

        collect($results)->each(function($item) {
            Notification::send(Profile::find($item->responsable_id), new ResponsableParticipationAModeredEnPriorite($item->total_count, $item->waiting_count, $item->in_progress_count));
        });
    }
}
