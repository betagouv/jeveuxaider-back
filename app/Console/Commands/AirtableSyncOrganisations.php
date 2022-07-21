<?php

namespace App\Console\Commands;

use App\Models\Structure;
use App\Services\Airtable;
use Illuminate\Console\Command;

class AirtableSyncOrganisations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airtable:sync-organisations {--fromId=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync organisations in Airtable {--fromId= : Take organisations > fromId }';

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
        $query = Structure::with(['missions'])->orderBy('id')->where('id', '>=', $options['fromId']);

        if ($this->confirm($query->count().' organisations will be added or updated in Airtable')) {
            $start = now();
            $time = $start->diffInSeconds(now());

            $this->comment("Processed in $time seconds");

            $query->chunk(50, function ($organisations) use ($start) {
                foreach ($organisations as $organisation) {
                    Airtable::syncStructure($organisation);
                    $this->comment('Processed organisation '.$organisation->id);
                }
                $time = $start->diffInSeconds(now());
                $this->comment("Processed in $time seconds");
            });
        }
    }
}
