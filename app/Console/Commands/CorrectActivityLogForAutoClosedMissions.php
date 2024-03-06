<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CorrectActivityLogForAutoClosedMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity-log:correct-log-for-auto-closed-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove causer from activity_logs for automatically closed missions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cleanMissionsCauser();
        $this->cleanParticipationsCauser();
    }

    private function cleanMissionsCauser()
    {
        $query = DB::table('activity_log AS al')
            ->whereNotNull(DB::raw("al.properties->'attributes'->>'automatically_closed_at'"))
            ->where(DB::raw("al.properties->'attributes'->>'state'"), '=', 'Terminée')
            ->where('subject_type', 'like', '%Mission')
            ->whereNotNull('al.causer_id');

        $this->info('Missions: ' . $query->count() . ' logs will be updated');

        if ($this->confirm('Do you wish to continue ?')) {
            $query->update([
                'causer_id' => null,
                'causer_type' => null
            ]);
        }
    }

    private function cleanParticipationsCauser()
    {
        $query = DB::table('activity_log AS al')
            ->where('event', 'updated - auto closed')
            ->whereNotNull('al.causer_id');

        $this->info('Participations: ' . $query->count() . ' logs will be updated');

        if ($this->confirm('Do you wish to continue ?')) {
            $query->update([
                'causer_id' => null,
                'causer_type' => null
            ]);
        }
    }
}
