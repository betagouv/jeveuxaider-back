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
        $results = DB::select(
            "
                SELECT missions_responsables.responsable_id, COUNT(*) As total_count,
                COUNT(*) filter(WHERE participations.state = 'En attente de validation' AND participations.created_at < (NOW() - INTERVAL '7 days')) As waiting_count,
                COUNT(*) filter(WHERE participations.state = 'En cours de traitement' AND participations.created_at < (NOW() - INTERVAL '2 months')) As in_progress_count
                FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN missions_responsables ON missions_responsables.mission_id = missions.id
                LEFT JOIN profiles ON profiles.id = missions_responsables.responsable_id
                WHERE missions_responsables.responsable_id IS NOT NULL
                AND (
                    (participations.state = 'En attente de validation' AND participations.created_at < (NOW() - INTERVAL '7 days'))
                    OR (participations.state = 'En cours de traitement' AND participations.created_at < (NOW() - INTERVAL '2 months'))
                )
                GROUP BY missions_responsables.responsable_id
                ORDER BY total_count DESC
            "
        );

        collect($results)->each(function ($item) {
            Notification::send(Profile::find($item->responsable_id), new ResponsableParticipationAModeredEnPriorite($item->total_count, $item->waiting_count, $item->in_progress_count));
        });
    }
}
