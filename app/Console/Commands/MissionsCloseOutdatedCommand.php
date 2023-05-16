<?php

namespace App\Console\Commands;

use App\Jobs\MissionCloseOutdatedJob;
use App\Models\Mission;
use Illuminate\Console\Command;
use Carbon\Carbon;

class MissionsCloseOutdatedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:close-outdated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close outdated missions after 20 days.';

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
        $query = Mission::with(['responsable'])->where('state', 'Validée')
            ->whereBetween('end_date', [
                Carbon::now()->subDays(20)->startOfDay(),
                Carbon::now()->subDays(20)->endOfDay(),
            ]);

        $query->pluck('id')->each(fn ($id) => MissionCloseOutdatedJob::dispatch($id));
    }
}
