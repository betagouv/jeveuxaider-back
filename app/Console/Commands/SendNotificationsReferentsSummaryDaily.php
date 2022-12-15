<?php

namespace App\Console\Commands;

use App\Models\Participation;
use App\Models\Profile;
use App\Notifications\ReferentSummaryDaily;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;

class SendNotificationsReferentsSummaryDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:referents-summary-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to referents of what happened yesterday';

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
        $referents = Profile::select('id', 'email')
            ->whereHas('user.roles', function (Builder $query){
                $query->where('roles.id', 3);
            })
            ->where('notification__referent_frequency', 'summary')
            ->get();

        foreach ($referents as $referent) {
            Notification::send(Profile::find($referent->id), new ReferentSummaryDaily($referent->id));
        }

    }
}
