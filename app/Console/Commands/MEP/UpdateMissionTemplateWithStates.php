<?php

namespace App\Console\Commands\MEP;

use App\Models\MissionTemplate;
use Illuminate\Console\Command;

class UpdateMissionTemplateWithStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:update-mission-templates-with-states';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update mission template with states';

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
        $query = MissionTemplate::where('published', true)->where('state', 'draft');
        $this->info($query->count().' published templates will be updated to validated');
        if ($this->confirm('Do you wish to continue?')) {
            $query->update(['state' => 'validated']);
        }

        $query = MissionTemplate::where('published', false);
        $this->info($query->count().' unpublished templates will be updated to waiting');
        if ($this->confirm('Do you wish to continue?')) {
            $query->update(['state' => 'waiting']);
        }
    }
}
