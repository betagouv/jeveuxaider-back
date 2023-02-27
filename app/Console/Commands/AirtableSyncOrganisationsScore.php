<?php

namespace App\Console\Commands;

use App\Models\Structure;
use App\Services\Airtable;
use Illuminate\Console\Command;

class AirtableSyncOrganisationsScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airtable:sync-organisations-score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync organisations score in Airtable';

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
        $query = Structure::with(['participations'])->where('response_time', '!=', null);

        $this->comment('Processed organisation '.$query->count());

        $query->chunk(50, function ($organisations) {
            foreach ($organisations as $organisation) {
                Airtable::syncStructure($organisation);
                $this->comment('Processed organisation '.$organisation->id);
            }
        });
        
    }
}
