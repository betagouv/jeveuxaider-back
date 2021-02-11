<?php

namespace App\Console\Commands;

use App\Models\Collectivity;
use Illuminate\Console\Command;

class AssignCoordonatesToCollectivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collectivities:coordonates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add latitude and longitude to collectivities';

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
        $queryBuilder = Collectivity::where('type', 'commune')->whereJsonLength('zips', '>', 0)->whereNull('latitude');
        $this->info($queryBuilder->count() . ' collectivities will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $collectivities = $queryBuilder->get();
            foreach ($collectivities as $collectivity) {
                $collectivity->setCoordonates();
                $collectivity->saveQuietly();
            }
        }
    }
}
