<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class MissionsSendTestimonyNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:send-testimony-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the testimony notifications for the past 2 month completed missions.';

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
        $query = Participation::where('state', 'Validée')
            ->whereDoesntHave('notificationTemoignage')
            ->whereDoesntHave('temoignage')
            ->whereHas('mission', function (Builder $query) {
                $query->where('state', 'Terminée')
                    ->where('start_date', '>=', Carbon::now()->startOfDay()->subMonths(2));
            });

        $this->info('Send the testimony notifications for the past 2 month completed missions.');
        $this->info($query->count().' notification(s) will be sent.');

        if ($this->confirm('Do you wish to continue?')) {
            foreach ($query->get() as $participation) {
                /** @var \App\Models\Participation $participation */
                $participation->sendNotificationTemoignage();
            }
        }
    }
}
