<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Participation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class HandleUnsubscribedOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:handle-unsubscribed-organizations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle missions & participations status for unsubscribed organizations';

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
        // Toutes les missions validées rattachées à une organisation désinscrite,
        // et dont la date de fin est passée.
        $outdatedMissions = Mission::where('state', 'Validée')
            ->organizationState("Désinscrite")
            ->outdated();

        // Toutes les missions validées rattachées à une organisation désinscrite,
        // et dont la date de fin n'est pas passée.
        $activeMissions = Mission::where('state', 'Validée')
            ->organizationState("Désinscrite")
            ->notOutdated();

        // Toutes les participations en attente de validation des missions rattachées à
        // une organisation désinscrite.
        $participations = Participation::where('state', "En attente de validation")
            ->whereHas('mission', function (Builder $query) {
                $query->where('state', 'Validée')
                ->whereHas('structure', function (Builder $query) {
                    $query->where('state', 'Désinscrite');
                });
            });

        $this->info("{$outdatedMissions->count()} outdated mission(s) will have their status set to ended.");
        $this->info("{$activeMissions->count()} active mission(s) will have their status set to cancelled.");
        $this->info("{$participations->count()} participation(s) in waiting for validation will be cancelled.");

        // ray("OUTDATED", $outdatedMissions->pluck('id')->toArray());
        // ray("ACTIVE", $activeMissions->pluck('id')->toArray());
        // ray("PARTICIPATIONS", $participations->pluck('id')->toArray());

        if ($this->confirm('Do you wish to continue?')) {
            // Notifs OFF
            $participations->update(['state' => 'Annulée']);
            $outdatedMissions->update(['state' => 'Terminée']);
            $activeMissions->update(['state' => 'Annulée']);
        }
    }
}
