<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Structure;
use App\Notifications\MissionInDraft;
use App\Notifications\NoNewMission;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class SendNotificationsNoNewMission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:no-new-mission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Responsables when no mission has been published since 3 months.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Structure::where('state', 'Validée')
        ->whereHas('missions', function (Builder $query) {
            return $query->whereBetween('created_at', [
                Carbon::now()->subMonths(3)->startOfDay(),
                Carbon::now()->subMonths(3)->endOfDay()
            ])
            // The last mission. Cannot use latest() here as it is not a simple orderBy.
            ->whereIn('id', function (QueryBuilder $query) {
                $query
                    ->selectRaw('max(id)')
                    ->from('missions')
                    ->whereColumn('structure_id', 'structures.id');
            });
        });

        foreach ($query->get() as $structure) {
            Notification::send($structure->responsables->first(), new NoNewMission($structure));
        }
    }
}
