<?php

namespace App\Console\Commands;

use App\Models\Participation;
use App\Models\Profile;
use App\Notifications\ResponsableSummaryDaily;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;

class SendNotificationsResponsablesSummaryDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:responsables-summary-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to responsables of what happened yesterday';

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
            ->whereHas('user.roles', function (Builder $query){
                $query->where('roles.id', 2);
            })
            ->where('notification__responsable_frequency', 'summary')
            ->get();

        foreach ($responsables as $responsable) {
            Notification::send(Profile::find($responsable->id), new ResponsableSummaryDaily($responsable->id));
        }

    }
}
