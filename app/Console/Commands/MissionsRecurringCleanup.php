<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class MissionsRecurringCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:clean-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove end_date for recurring missions';

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
        $query = Mission::where('date_type', 'recurring')
            ->whereNotNull('end_date');

        if ($this->confirm("Retirer la date de fin aux " . $query->count() . " missions récurrentes ?")) {
            $query->update([
                'end_date' => null
            ]);
        }
    }
}
