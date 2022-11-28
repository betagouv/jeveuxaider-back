<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AlgoliaMissionUpdateFieldOutdated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'algolia:update-field-outdated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update field outdated for missions';

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
        // Get end date between yesterday and before today 0:0:00
        Mission::with('structure')->where('end_date', '<', Carbon::today())
            ->where('end_date', '>=', Carbon::yesterday())
            ->get()
            ->each(function ($mission, $key) {
                if ($mission->shouldBeSearchable()) {
                    $mission->searchable();
                }
            });
    }
}
