<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class GenerateSlugForMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slug for missions';

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
     * @return int
     */
    public function handle()
    {
        $missions = Mission::whereNull('slug')->get();
        $this->info($missions->count() . ' missions will be updated.');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($missions as $mission) {
                $mission->save();
            }
        }
    }
}
