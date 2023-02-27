<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\ResponsableSummaryMonthly;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;

class SendNotificationsResponsablesSummaryMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string@
     */
    protected $signature = 'notification:responsables-summary-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to responsables of what happened last month';

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
        $responsables = Profile::select('id', 'email')
            ->where('notification__responsable_bilan', true)
            ->whereHas('user.structures', function (Builder $query){
                $query->where('state', 'Validée');
            })
            ->whereHas('user.structures.participations')
            ->whereHas('user.roles', function (Builder $query){
                $query->where('roles.id', 2);
            })
            ->get();

        foreach ($responsables as $responsable) {
            Notification::send(Profile::find($responsable->id), new ResponsableSummaryMonthly($responsable->id));
        }

    }
}
