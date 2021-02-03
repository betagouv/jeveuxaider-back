<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class AssignThumbnailToMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:assign-thumbnail-to-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a thumbnail to missions that are not templates';

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
        $queryBuilder = Mission::whereNull('template_id');
        $this->info($queryBuilder->count() . ' missions will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $missions = $queryBuilder->get();
            foreach ($missions as $mission) {
                $mission->thumbnail = $mission->domaine_id . '_' . rand(1, 3);
                $mission->saveQuietly();
            }
        }
    }
}
