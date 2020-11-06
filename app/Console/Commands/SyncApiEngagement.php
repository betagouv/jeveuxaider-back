<?php

namespace App\Console\Commands;

use App\Services\ApiEngagement;
use Illuminate\Console\Command;

class SyncApiEngagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apiengagement:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync missions from Api Engagement';

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
        $api = new ApiEngagement();
        $api->delete();
        $api->import();
    }
}
