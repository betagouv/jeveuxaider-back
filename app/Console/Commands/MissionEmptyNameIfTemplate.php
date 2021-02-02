<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class MissionEmptyNameIfTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:mission-empty-name-if-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty mission name if link to template';

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
        $query = Mission::withTrashed()->whereNotNull('template_id')->whereNotNull('name');
        $this->info($query->count() . ' names will be set to null');

        if ($this->confirm('Do you wish to continue?')) {
            $query->update(['name'=>null]);
        }
    }
}
