<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;

class StatisticsController extends Controller
{
    public function dashboard(Request $request)
    {
        $missionsAvailable = Mission::role($request->header('Context-Role'))
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        switch ($request->header('Context-Role')) {
            case 'admin':
            case 'analyste':
                return [
                    'organisations' => Structure::count(),
                    'organisations_actives' => $missionsAvailable->pluck('structure_id')->unique()->count(),
                    'missions' => Mission::count(),
                    'missions_actives' => $missionsAvailable->count(),
                    'participations' => Participation::count(),
                    'participations_validated' => Participation::where('state', 'Validée')->count(),
                    'places_left' => $placesLeft,
                    'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
                    'users' => Profile::count(),
                    'users_benevoles' => Profile::benevole()->count(),
                ];
                break;
            case 'referent':
            case 'referent_regional':
            case 'tete_de_reseau':
            case 'responsable_territoire':
                return [
                    'organisations' => Structure::role($request->header('Context-Role'))->count(),
                    'organisations_actives' => $missionsAvailable->pluck('structure_id')->unique()->count(),
                    'missions' => Mission::role($request->header('Context-Role'))->count(),
                    'missions_actives' => $missionsAvailable->count(),
                    'participations' => Participation::role($request->header('Context-Role'))->count(),
                    'participations_validated' => Participation::role($request->header('Context-Role'))->where('state', 'Validée')->count(),
                    'places_left' => $placesLeft,
                    'places_left_waiting' => Mission::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation','En cours de traitement'])->sum('places_left'),
                    'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
                ];
                break;
            case 'responsable':
                return [
                    'missions' => Mission::role($request->header('Context-Role'))->count(),
                    'missions_actives' => $missionsAvailable->count(),
                    'participations' => Participation::role($request->header('Context-Role'))->count(),
                    'participations_validated' => Participation::role($request->header('Context-Role'))->where('state', 'Validée')->count(),
                    'places_left' => $placesLeft,
                    'places_left_waiting' =>  Mission::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation','En cours de traitement'])->sum('places_left'),
                    'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
                ];
                break;
        }
    }

    public function organisations(Request $request, Structure $structure)
    {
        $missionsStateCount = array_map(function ($state) use ($structure) {
            return $structure->missions()->where('state', $state)->count();
        }, config('taxonomies.mission_workflow_states.terms'));

        $participationsStateCount = array_map(function ($state) use ($structure) {
            return $structure->participations()->where('participations.state', $state)->count();
        }, config('taxonomies.participation_workflow_states.terms'));

        $places_left = $structure->places_left;
        $places_offered = $structure->places_offered;

        return [
            'missions_total' => $structure->missions()->count(),
            'missions_available' => $structure->missions()->available()->count(),
            'missions_state' =>  $missionsStateCount,
            'participations_total' => $structure->participations()->count(),
            'participations_state' =>  $participationsStateCount,
            'places_left' => $places_left,
            'places_left_waiting' => $structure->missions()->whereIn('state', ['En attente de validation','En cours de traitement'])->sum('places_left'),
            'places_occupation_rate' => $places_left ? round((($places_offered - $places_left) / $places_offered) * 100) : 0,
            'response_ratio' => $structure->response_ratio,
            'response_time' => $structure->response_time,
            'score_response_time' => $structure->response_time_score,
        ];
    }

    public function missions(Request $request, Mission $mission)
    {
        $participationsStateCount = array_map(function ($state) use ($mission) {
            return $mission->participations()->where('participations.state', $state)->count();
        }, config('taxonomies.participation_workflow_states.terms'));


        return [
            'participations_total' => $mission->participations()->count(),
            'participations_state' =>  $participationsStateCount,
        ];
    }

    public function reseaux(Request $request, Reseau $reseau)
    {
        $missionsStateCount = array_map(function ($state) use ($reseau) {
            return $reseau->missions()->where('missions.state', $state)->count();
        }, config('taxonomies.mission_workflow_states.terms'));

        $participationsStateCount = array_map(function ($state) use ($reseau) {
            return $reseau->participations()->where('participations.state', $state)->count();
        }, config('taxonomies.participation_workflow_states.terms'));

        $missionsAvailable = Mission::ofReseau($reseau->id)
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
            'organisations' => Structure::ofReseau($reseau->id)->count(),
            'organisations_actives' => Mission::ofReseau($reseau->id)->pluck('structure_id')->unique()->count(),
            'missions' => $reseau->missions()->count(),
            'missions_actives' => $reseau->missions()->available()->count(),
            'missions_state' =>  $missionsStateCount,
            'participations' => $reseau->participations()->count(),
            'participations_state' =>  $participationsStateCount,
            'places_left' => $placesLeft,
            'places_left_waiting' => Mission::ofReseau($reseau->id)->whereIn('state', ['En attente de validation','En cours de traitement'])->sum('places_left'),
            'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
        ];
    }
}
