<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;
use Illuminate\Console\Command;
use Carbon\Carbon;

class NotificationsReferent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:referents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Referents when new content';

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
        $nb_jours_notif = 10;

        $structuresByDepartment = Structure::where('state', 'En attente de validation')
          //->where('created_at', '>', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->where('created_at', '<=', Carbon::now()->subDays(1)->endOfDay())
          ->whereNotNull('department')
          ->get()
          ->groupBy('department')->toArray();

        $missionsByDepartment = Mission::where('state', 'En attente de validation')
          ->where('created_at', '>', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->where('created_at', '<=', Carbon::now()->subDays(1)->endOfDay())
          ->whereNotNull('department')
          ->get()
          ->groupBy('department')->toArray();

        $structuresAndMissionsByDepartment = $this->mergeArrays($missionsByDepartment, $structuresByDepartment);

        foreach ($structuresAndMissionsByDepartment as $department => $structuresAndMissions) {
            $structures = array_filter($structuresAndMissions, function ($item) {
                return !isset($item['structure_id']);
            });
            $missions = array_filter($structuresAndMissions, function ($item) {
                return isset($item['structure_id']);
            });

            $this->info($department . ' has ' . count($structures) . ' organisations, and ' . count($missions) . ' missions');
            $referents = Profile::where('referent_department', $department)->get()->pluck('email');
            $this->info($referents);
        }
    }

    private function mergeArrays($ar1, $ar2)
    {
        return $ar1;
        foreach ($ar2 as $key => $value) {
            if (isset($ar1[$key])) {
                array_push($ar1[$key], ...$value);
            } else {
                $ar1[$key] = $value;
            }
        }

        return $ar1;
    }
}
