<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;
use App\Notifications\ModerateurDailyTodo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationTodoToModerateurs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:moderateurs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Moderateurs when new content';

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
        $nb_jours_notif = 3;

        $structuresByDepartment = Structure::where('state', 'En attente de validation')
          ->where('created_at', '<', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->whereNotNull('department')
          ->get()
          ->groupBy('department')->toArray();

        $missionsByDepartment = Mission::where('state', 'En attente de validation')
          ->where('created_at', '<', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->whereNotNull('department')
          ->get()
          ->groupBy('department')->toArray();

        $structuresAndMissionsByDepartment = $this->mergeArrays($missionsByDepartment, $structuresByDepartment);

        $byDeparment = [];
        foreach ($structuresAndMissionsByDepartment as $department => $structuresAndMissions) {
            $structures = array_filter($structuresAndMissions, function ($structureOrMission) {
                return array_key_exists('siret', $structureOrMission);
            });
            $missions = array_filter($structuresAndMissions, function ($structureOrMission) {
                return array_key_exists('structure_id', $structureOrMission);
            });
            $byDeparment[$department]['department_name'] = config('taxonomies.departments.terms')[$department];
            $byDeparment[$department]['missions'] = $missions;
            $byDeparment[$department]['structures'] = $structures;
            $byDeparment[$department]['referents'] = Profile::where('referent_department', $department)->get();
        }
        Notification::route('mail', 'giulietta.bressy@gmail.com')->notify(new ModerateurDailyTodo($byDeparment));
    }

    private function mergeArrays($ar1, $ar2)
    {
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
