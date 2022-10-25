<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;
use App\Notifications\ReferentDailyTodo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendNotificationTodoToReferents extends Command
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
          ->where('created_at', '>', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->whereNotNull('department')
          ->get()
          ->groupBy('department')->toArray();

        $this->info(implode(', ', array_keys($structuresByDepartment)).' départements avec des structures en attente');

        $missionsByDepartment = Mission::where('state', 'En attente de validation')
          ->where('created_at', '>', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->whereNotNull('department')
          ->get()
          ->groupBy('department')->toArray();

        $this->info(implode(', ', array_keys($missionsByDepartment)).' départements avec des missions en attente');

        $structuresAndMissionsByDepartment = $this->mergeArrays($missionsByDepartment, $structuresByDepartment);

        foreach ($structuresAndMissionsByDepartment as $department => $structuresAndMissions) {
            $structures = array_filter($structuresAndMissions, function ($structureOrMission) {
                return array_key_exists('siret', $structureOrMission);
            });
            $missions = array_filter($structuresAndMissions, function ($structureOrMission) {
                return array_key_exists('structure_id', $structureOrMission);
            });

            $this->info($department.' has '.count($structures).' organisations, and '.count($missions).' missions');
            $referents = Profile::whereHas('user.departmentsAsReferent', function (Builder $query) use ($department) {
                $query->where('number', $department);
            })->get();
            $this->info($referents->pluck('email'));
            Notification::send($referents, new ReferentDailyTodo($structures, $missions));
        }
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
