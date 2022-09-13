<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Message;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\NotificationBenevole;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Reseau;
use Carbon\Carbon;
use App\Models\Structure;
use App\Models\Territoire;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PublicNumbersController extends Controller
{
    public $year;
    public $department;

    public function __construct(Request $request)
    {
        $this->year = $request->input('year');
        // $this->department = $request->input('department');
    }

    public function overview(Request $request)
    {

        return [
            'organisations' => Structure::whereIn('state', ['Validée'])->whereYear('created_at', $this->year)->count(),
            'missions' => Mission::whereIn('state', ['Validée', 'Terminée'])->whereYear('created_at', $this->year)->count(),
            'participations' => Participation::whereIn('state', ['Validée'])->whereYear('created_at', $this->year)->count(),
            'benevoles' => User::whereYear('created_at', $this->year)->where('context_role', 'volontaire')->count(),
        ];
    }

    public function participationsByDomaines(Request $request)
    {

        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN domaines ON domaines.id = mission_templates.domaine_id OR domaines.id = missions.domaine_id OR domaines.id = missions.domaine_secondary_id
                WHERE missions.deleted_at IS NULL
                AND date_part('year', participations.created_at) = :year
                AND participations.state IN ('Validée')
                AND domaines.name IS NOT NULL
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
            ", [
            "year" => $this->year,
        ]);

        return $results;
    }

    public function participationsByActivities(Request $request)
    {

        $results = DB::select("
            SELECT activities.name, activities.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
            LEFT JOIN activities ON activities.id = mission_templates.activity_id OR activities.id = missions.activity_id
            WHERE participations.deleted_at IS NULL
            AND missions.deleted_at IS NULL
            AND date_part('year', participations.created_at) = :year
            AND participations.state IN ('Validée')
            AND activities.name IS NOT NULL
            GROUP BY activities.name, activities.id
            ORDER BY count DESC
        ", [
            "year" => $this->year,
        ]);

        return $results;
    }

    public function overviewUtilisateurs(Request $request)
    {

        return [
            'utilisateurs' => User::whereYear('created_at', $this->year)->count(),
            'benevoles' => User::whereYear('created_at', $this->year)->where('context_role', 'volontaire')->count(),
        ];
    }

    public function utilisateursByAge(Request $request)
    {


        $results = DB::select("
            SELECT COUNT(age),
            CASE
                WHEN age <= 18 THEN 'LESS_THAN_18'
                WHEN age > 18 AND age <= 25 THEN 'BETWEEN_18_AND_25'
                WHEN age > 25 AND age <= 35 THEN 'BETWEEN_25_AND_35'
                WHEN age > 35 AND age <= 45 THEN 'BETWEEN_35_AND_45'
                WHEN age > 45 AND age <= 55 THEN 'BETWEEN_45_AND_55'
                WHEN age > 55 AND age <= 65 THEN 'BETWEEN_55_AND_65'
                WHEN age > 65 AND age <= 75 THEN 'BETWEEN_65_AND_75'
                WHEN age > 75 THEN 'MORE_THAN_75'
            END age_range
            FROM (
                SELECT extract('year' from AGE(profiles.created_at, profiles.birthday)) As age FROM profiles
                WHERE date_part('year', profiles.created_at) = :year
                AND profiles.birthday IS NOT NULL
            ) TableAlias
            GROUP BY age_range
        ", [
            "year" => $this->year,
        ]);


        return collect($results)->pluck('count', 'age_range');
    }

    public function structuresByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', structures.created_at) AS created_at,
                date_part('year', structures.created_at) as year,
                date_part('month', structures.created_at) as month,
                count(*) AS structures_total,
                sum(case when structures.state  = 'Brouillon' then 1 else 0 end) as structures_draft,
                sum(case when structures.state  = 'En attente de validation' then 1 else 0 end) as structures_waiting_validation,
                sum(case when structures.state  = 'En cours de traitement' then 1 else 0 end) as structures_being_processed,
                sum(case when structures.state  = 'Validée' then 1 else 0 end) as structures_validated,
                sum(case when structures.state  = 'Signalée' then 1 else 0 end) as structures_signaled,
                sum(case when structures.state  = 'Désinscrite' then 1 else 0 end) as structures_unsubscribed
            FROM structures
            WHERE structures.deleted_at IS NULL
            AND structures.department ILIKE :department
            GROUP BY date_trunc('month', structures.created_at), year, month
            ORDER BY date_trunc('month', structures.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                $results[$index]->structures_validated_variation = (($item->structures_validated - $results[$index + 12]->structures_validated) / $results[$index + 12]->structures_validated) * 100;
            }
        }

        return $results;
    }

    public function structuresByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', structures.created_at) AS created_at,
                date_part('year', structures.created_at) as year,
                count(*) AS structures_total,
                sum(case when structures.state  = 'Brouillon' then 1 else 0 end) as structures_draft,
                sum(case when structures.state  = 'En attente de validation' then 1 else 0 end) as structures_waiting_validation,
                sum(case when structures.state  = 'En cours de traitement' then 1 else 0 end) as structures_being_processed,
                sum(case when structures.state  = 'Validée' then 1 else 0 end) as structures_validated,
                sum(case when structures.state  = 'Signalée' then 1 else 0 end) as structures_signaled,
                sum(case when structures.state  = 'Désinscrite' then 1 else 0 end) as structures_unsubscribed
            FROM structures
            WHERE structures.deleted_at IS NULL
            AND structures.department ILIKE :department
            GROUP BY date_trunc('year', structures.created_at), year
            ORDER BY date_trunc('year', structures.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                $results[$index]->structures_validated_variation = (($item->structures_validated - $results[$index + 1]->structures_validated) / $results[$index + 1]->structures_validated) * 100;
            }
        }

        return $results;
    }

    public function missionsByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', missions.created_at) AS created_at,
                date_part('year', missions.created_at) as year,
                date_part('month', missions.created_at) as month,
                count(*) AS missions_total,
                sum(case when missions.state  = 'Brouillon' then 1 else 0 end) as missions_draft,
                sum(case when missions.state  = 'En attente de validation' then 1 else 0 end) as missions_waiting_validation,
                sum(case when missions.state  = 'En cours de traitement' then 1 else 0 end) as missions_being_processed,
                sum(case when missions.state  = 'Validée' then 1 else 0 end) as missions_validated,
                sum(case when missions.state  = 'Signalée' then 1 else 0 end) as missions_signaled,
                sum(case when missions.state  = 'Terminée' then 1 else 0 end) as missions_finished,
                sum(case when missions.state  = 'Annulée' then 1 else 0 end) as missions_canceled,
                sum(case when missions.state IN ('Validée','Terminée') then 1 else 0 end) as missions_posted
            FROM missions
            WHERE missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('month', missions.created_at), year, month
            ORDER BY date_trunc('month', missions.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                $results[$index]->missions_posted_variation = (($item->missions_posted - $results[$index + 12]->missions_posted) / $results[$index + 12]->missions_posted) * 100;
            }
        }

        return $results;
    }

    public function missionsByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', missions.created_at) AS created_at,
                date_part('year', missions.created_at) as year,
                count(*) AS missions_total,
                sum(case when missions.state  = 'Brouillon' then 1 else 0 end) as missions_draft,
                sum(case when missions.state  = 'En attente de validation' then 1 else 0 end) as missions_waiting_validation,
                sum(case when missions.state  = 'En cours de traitement' then 1 else 0 end) as missions_being_processed,
                sum(case when missions.state  = 'Validée' then 1 else 0 end) as missions_validated,
                sum(case when missions.state  = 'Signalée' then 1 else 0 end) as missions_signaled,
                sum(case when missions.state  = 'Terminée' then 1 else 0 end) as missions_finished,
                sum(case when missions.state  = 'Annulée' then 1 else 0 end) as missions_canceled,
                sum(case when missions.state IN ('Validée','Terminée') then 1 else 0 end) as missions_posted
            FROM missions
            WHERE missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('year', missions.created_at), year
            ORDER BY date_trunc('year', missions.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                $results[$index]->missions_posted_variation = (($item->missions_posted - $results[$index + 1]->missions_posted) / $results[$index + 1]->missions_posted) * 100;
            }
        }

        return $results;
    }

    public function participationsByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', participations.created_at) AS created_at,
                date_part('year', participations.created_at) as year,
                date_part('month', participations.created_at) as month,
                count(*) AS participations_total,
                sum(case when participations.state  = 'En attente de validation' then 1 else 0 end) as participations_waiting_validation,
                sum(case when participations.state  = 'En cours de traitement' then 1 else 0 end) as participations_being_processed,
                sum(case when participations.state  = 'Validée' then 1 else 0 end) as participations_validated,
                sum(case when participations.state  = 'Refusée' then 1 else 0 end) as participations_refused,
                sum(case when participations.state  = 'Annulée' then 1 else 0 end) as participations_canceled
            FROM participations
            LEFT JOIN missions ON participations.mission_id = missions.id
            WHERE participations.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('month', participations.created_at), year, month
            ORDER BY date_trunc('month', participations.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                if ($results[$index + 12]->participations_total) {
                    $results[$index]->participations_total_variation = (($item->participations_total - $results[$index + 12]->participations_total) / $results[$index + 12]->participations_total) * 100;
                }
                if ($results[$index + 12]->participations_validated) {
                    $results[$index]->participations_validated_variation = (($item->participations_validated - $results[$index + 12]->participations_validated) / $results[$index + 12]->participations_validated) * 100;
                }
            }

            if($item->participations_total){
                $results[$index]->participations_conversion = ($item->participations_validated / $item->participations_total) * 100;
            }
        }

        return $results;
    }

    public function participationsByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', participations.created_at) AS created_at,
                date_part('year', participations.created_at) as year,
                count(*) AS participations_total,
                sum(case when participations.state  = 'En attente de validation' then 1 else 0 end) as participations_waiting_validation,
                sum(case when participations.state  = 'En cours de traitement' then 1 else 0 end) as participations_being_processed,
                sum(case when participations.state  = 'Validée' then 1 else 0 end) as participations_validated,
                sum(case when participations.state  = 'Refusée' then 1 else 0 end) as participations_refused,
                sum(case when participations.state  = 'Annulée' then 1 else 0 end) as participations_canceled
            FROM participations
            LEFT JOIN missions ON participations.mission_id = missions.id
            WHERE participations.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('year', participations.created_at), year
            ORDER BY date_trunc('year', participations.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                if ($results[$index + 1]->participations_total) {
                    $results[$index]->participations_total_variation = (($item->participations_total - $results[$index + 1]->participations_total) / $results[$index + 1]->participations_total) * 100;
                }
                if ($results[$index + 1]->participations_validated) {
                    $results[$index]->participations_validated_variation = (($item->participations_validated - $results[$index + 1]->participations_validated) / $results[$index + 1]->participations_validated) * 100;
                }
            }
            if($item->participations_total){
                $results[$index]->participations_conversion = ($item->participations_validated / $item->participations_total) * 100;
            }
        }

        return $results;
    }

    public function usersByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', profiles.created_at) AS created_at,
                date_part('year', profiles.created_at) as year,
                date_part('month', profiles.created_at) as month,
                count(*) AS profiles_total
            FROM profiles
            WHERE profiles.department ILIKE :department
            GROUP BY date_trunc('month', profiles.created_at), year, month
            ORDER BY date_trunc('month', profiles.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                $results[$index]->profiles_total_variation = (($item->profiles_total - $results[$index + 12]->profiles_total) / $results[$index + 12]->profiles_total) * 100;
            }
        }

        return $results;
    }

    public function usersByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', profiles.created_at) AS created_at,
                date_part('year', profiles.created_at) as year,
                count(*) AS profiles_total
            FROM profiles
            WHERE profiles.department ILIKE :department
            GROUP BY date_trunc('year', profiles.created_at), year
            ORDER BY date_trunc('year', profiles.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                $results[$index]->profiles_total_variation = (($item->profiles_total - $results[$index + 1]->profiles_total) / $results[$index + 1]->profiles_total) * 100;
            }
        }

        return $results;
    }
}
