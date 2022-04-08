<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Services\Airtable;
use Illuminate\Console\Command;

class AirtableSyncMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airtable:sync-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync missions in Airtable';

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
        $query = Mission::with(['structure', 'domaine', 'template.domaine'])->whereIn('state', ['En attente de validation', 'En cours de traitement', 'Validée']);

        if ($this->confirm($query->count() . ' missions will be added or updated in Airtable')) {
            $start = now();
            $time = $start->diffInSeconds(now());

            $this->comment("Processed in $time seconds");

            $query->chunk(50, function ($missions) use ($start) {
                foreach ($missions as $mission) {
                    Airtable::syncMission($mission);
                }
                $time = $start->diffInSeconds(now());
                $this->comment("Processed in $time seconds");
            });
        }
    }
}
