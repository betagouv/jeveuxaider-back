<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Console\Command;

class ParticipationsMergeCurrentToValidated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participation:merge-current-to-validated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge current participations to validated state';

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

        $participations = Participation::where('state', 'Mission en cours');

        $count = $participations->count();
        if ($this->confirm($count .' participation(s) will be validated')) {
            $participations->update(['state' => 'Mission validée']);
            $this->info($count . ' participation(s) has been validated. No notification has been sent.');
        }
    }
}
