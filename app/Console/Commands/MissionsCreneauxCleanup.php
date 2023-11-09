<?php

namespace App\Console\Commands;

use App\Jobs\MissionWithSlotsSyncDatesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MissionsCreneauxCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:clean-creneaux';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync start_date and end_date for missions with slots';

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
            SELECT id, start_date, end_date, dates
            FROM missions
            WHERE dates IS NOT NULL
            AND start_date::date <> (dates->0->>'date')::date
            AND (dates->0->>'date') NOT LIKE '%Z'
            AND state NOT IN ('Terminée', 'Annulée')
            AND deleted_at IS NULL
            ORDER BY id DESC
        ");

        $missionIds = collect($missionIds)->pluck('id');

        if ($this->confirm($missionIds->count() . " missions vont être modifiés. Continuer ?")) {
            $missionIds->each(fn ($id) => MissionWithSlotsSyncDatesJob::dispatch($id));
        }
    }
}
