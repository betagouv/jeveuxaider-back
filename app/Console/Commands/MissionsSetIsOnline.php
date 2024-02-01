<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class MissionsSetIsOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions-set-is-online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set is online on missions';

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
        Mission::onlyTrashed()->get()->each(function ($mission) {
            $mission->is_online = false;
            $mission->timestamps = false;
            $mission->saveQuietly();
        });

        Mission::with(['structure'])->where('is_online', true)->get()->each(function ($mission) {
            $mission->is_online = false;
            if($mission->structure && $mission->structure->state === 'Validée') {
                if ($mission->state === 'Validée' || $mission->state === 'Terminée') {
                    $mission->is_online = true;
                }
            }

            $mission->timestamps = false;
            $mission->saveQuietly();
        });

    }
}
