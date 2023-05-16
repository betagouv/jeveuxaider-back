<?php

namespace App\Console\Commands;

use App\Jobs\MissionClose;
use App\Models\Mission;
use Illuminate\Console\Command;
use Carbon\Carbon;

class MissionsCloseOutdatedForAtLeast21Days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:close-outdated-for-at-least-21-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close outdated missions for 21 days or more.';

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
            ->where('end_date', '<', Carbon::now()->subDays(21)->startOfDay());
        $ids = $query->pluck('id');

        if ($this->confirm($ids->count() . " missions vont être automatiquement terminées. Les participations en attente et en cours de traitement seront refusées (sans envoi de notification). Les participations validées recevront la notif de témoignage.")) {
            $ids->each(fn ($id) => MissionClose::dispatch($id));
        }
    }
}
