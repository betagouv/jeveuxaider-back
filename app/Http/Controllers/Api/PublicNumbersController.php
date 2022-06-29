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

    public function __construct(Request $request)
    {
        $this->year = $request->input('year');
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
            LIMIT 5
        ", [
            "year" => $this->year,
        ]);

        return $results;
    }
}
