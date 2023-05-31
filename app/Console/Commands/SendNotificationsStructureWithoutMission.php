<?php

namespace App\Console\Commands;

use App\Models\Structure;
use App\Notifications\StructureWithoutMissionFirstReminder;
use App\Notifications\StructureWithoutMissionSecondReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsStructureWithoutMission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:structure-without-mission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Responsables when their organization has no mission yet.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Structure::with(['members'])->where('state', 'Validée')
            ->whereDate('created_at', Carbon::now()->subDays(10))
            ->whereDoesntHave('missions');

        foreach ($query->get() as $structure) {
            Notification::send($structure->members, new StructureWithoutMissionFirstReminder($structure));
        }

        $query = Structure::with(['members'])->where('state', 'Validée')
            ->whereDate('created_at', Carbon::now()->subDays(30))
            ->whereDoesntHave('missions');

        foreach ($query->get() as $structure) {
            Notification::send($structure->members, new StructureWithoutMissionSecondReminder($structure));
        }
    }
}
