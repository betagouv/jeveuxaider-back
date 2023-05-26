<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Services\Airtable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class AirtableSyncMissionsWithTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airtable:sync-missions-with-tag {tagId} {--fromId=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync missions with tag in Airtable {--fromId= : Take missions > fromId }';

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
        $options = $this->options();
        $tagId = $this->argument('tagId');
        $query = Mission::whereHas('structure')->with(['structure', 'domaine', 'template.domaine', 'template.activity', 'activity', 'tags'])
            ->whereHas('tags', function (Builder $query) use ($tagId) {
                $query->where('id', $tagId);
            })
            ->where('id', '>=', $options['fromId'])
            ->orderBy('id');

        if ($this->confirm($query->count().' missions will be added or updated in Airtable')) {
            $start = now();
            $time = $start->diffInSeconds(now());

            $this->comment("Processed in $time seconds");

            $query->chunk(50, function ($missions) use ($start) {
                foreach ($missions as $mission) {
                    Airtable::syncMission($mission);
                    $this->comment('Processed mission '.$mission->id);
                }
                $time = $start->diffInSeconds(now());
                $this->comment("Processed in $time seconds");
            });
        }
    }
}
