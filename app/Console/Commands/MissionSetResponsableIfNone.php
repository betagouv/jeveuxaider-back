<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MissionSetResponsableIfNone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:set-responsable-if-none';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tente d\'assigner un responsable aux missions dont le responsable ne fait plus partie de l\'organisation';

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
        $missionIds = DB::select("
            SELECT missions.id
            FROM missions
            INNER JOIN rolables ON rolables.rolable_id = missions.structure_id AND rolables.rolable_type = 'App\Models\Structure' AND rolables.role_id = 2
            WHERE missions.deleted_at IS NULL
            AND missions.responsable_id NOT IN (
                SELECT profiles.id
                FROM profiles
                INNER JOIN users ON users.id = profiles.user_id
                LEFT JOIN rolables ON rolables.user_id = users.id
                WHERE rolables.rolable_type = 'App\Models\Structure'
                AND rolables.role_id = 2
                AND rolables.rolable_id = missions.structure_id
            )
            GROUP BY missions.id
            ORDER BY missions.id ASC
        ");

        $missionIds = collect($missionIds)->pluck('id');

        if ($this->confirm("Ce script va tenter d'assigner un responsable aux missions dont le responsable ne fait plus partie de l'organisation rattachée. \n NB missions: " . $missionIds->count() . " \n Continuer ?")) {

            $bar = $this->output->createProgressBar($missionIds->count());
            $bar->start();

            foreach ($missionIds as $id) {
                $mission = Mission::find($id)->loadMissing('structure');
                if ($mission->structure && $mission->structure->members->count() > 0) {
                    $oldResponsableUser = $mission->responsable->user;
                    $newResponsableUser = $mission->structure->members->first();
                    if ($oldResponsableUser && $oldResponsableUser->profile && $newResponsableUser && $newResponsableUser->profile) {
                        $this->transferConversations($oldResponsableUser, $newResponsableUser, $mission);
                        $this->assignNewresponsable($oldResponsableUser, $newResponsableUser, $mission);
                        $this->logInfos($oldResponsableUser, $newResponsableUser, $mission);
                    }
                }

                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function transferConversations($oldResponsableUser, $newResponsableUser, $mission)
    {
        $participations = $mission->participations()->pluck('id')->toArray();
        $conversationsQuery = $oldResponsableUser->conversations()->whereIn('conversable_id', $participations);
        foreach ($conversationsQuery->get() as $conversation) {
            $conversation->users()->syncWithoutDetaching([$newResponsableUser->id]);
            $newResponsableUser->conversations()->updateExistingPivot($conversation->id, [
                'read_at' => Carbon::now(),
            ]);
        }
    }

    private function assignNewresponsable($oldResponsableUser, $newResponsableUser, $mission)
    {
        $mission->responsable_id = $newResponsableUser->profile->id;
        $mission->timestamps = false;
        $mission->saveQuietly();

        // Log (because saveQuietly)
        activity()
            ->performedOn($mission)
            ->withProperties([
                'attributes' => ['responsable_id' => $newResponsableUser->profile->id],
                'old' => ['responsable_id' => $oldResponsableUser->profile->id,]
            ])
            ->event('updated')
            ->log('updated');
    }

    private function logInfos($oldResponsableUser, $newResponsableUser, $mission)
    {
        $date = (\Carbon\Carbon::now())->toDateString();
        $storagePath = "logs/{$date}-command-mission-set-responsable-if-none.log";

        Log::build([
            'driver' => 'single',
            'path' => storage_path($storagePath),
        ])->info("mission:set-responsable-if-none", [
            'mission' => $mission->id,
            'old responsable_id' => $oldResponsableUser->profile->id,
            'new responsable_id' => $newResponsableUser->profile->id,
        ]);
    }
}
