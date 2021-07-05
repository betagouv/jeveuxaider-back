<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\MissionTemplate;
use Illuminate\Console\Command;

class MissionsGenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate slugs for all missions';

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
        $missions = Mission::all();
        $this->info($missions->count() . ' missions will be updated.');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($missions as $mission) {
                $mission->generateSlug();
                $mission->saveQuietly();
            }
        }
    }
}
