<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Release;
use App\Models\Structure;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Thematique;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActionController extends Controller
{

    public function index(Request $request)
    {
        $actions =  [];
        $user = $request->user();

        switch($request->header('Context-Role')) {
            case 'admin':
                $actions[] = [
                    'type' => 'organisations_waiting_validation',
                    'value' => Structure::where('state', 'En attente de validation')->count()
                ];
                $actions[] = [
                    'type' => 'organisations_incomplete',
                    'value' => Structure::count()
                ];
                $actions[] = [
                    'type' => 'missions_waiting_validation',
                    'value' => Mission::where('state', 'En attente de validation')->count(),
                ];
                $actions[] = [
                    'type' => 'missions_outdated',
                    'value' => Mission::where('end_date', '<', Carbon::now())->where('state', 'Validée')->count(),
                ];
                break;
            case 'responsable':
                $actions[] = [
                    'type' => 'messages_unread',
                    'value' => $user->getUnreadConversationsCount()
                ];
                $actions[] = [
                    'type' => 'participations_waiting_validation',
                    'value' => Participation::role($request->header('Context-Role'))->where('state', 'En attente de validation')->count(),
                ];
                $actions[] = [
                    'type' => 'participations_in_progress',
                    'value' => Participation::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->count(),
                ];
                $actions[] = [
                    'type' => 'missions_outdated',
                    'value' => Mission::role($request->header('Context-Role'))->where('end_date', '<', Carbon::now())->where('state', 'Validée')->count(),
                ];
                break;
        }

        return $actions;
    }

    // public function index(Request $request)
    // {

    //     $actions =  [];

    //     $user = $request->user();

    //     if ($unread_messages_count = $user->getUnreadConversationsCount()) {
    //         array_push($actions, [
    //             'type' => 'unread_messages',
    //             'value' => $unread_messages_count,
    //         ]);
    //     }

    //     $structures = $user->structures;

    //     foreach ($structures as $structure) {
    //         if ($waiting_participations_count = $structure->participations->where('state', 'En attente de validation')->count()) {
    //             array_push($actions, [
    //                 'type' => 'waiting_participations',
    //                 'value' => $waiting_participations_count,
    //                 'structure' => $structure,
    //             ]);
    //         }
    //         if ($outdated_missions_count = $structure->missions->where('end_date', '<', Carbon::now())->count()) {
    //             array_push($actions, [
    //                 'type' => 'outdated_missions',
    //                 'value' => $outdated_missions_count,
    //                 'structure' => $structure,
    //             ]);
    //         }
    //     }

    //     //outdated_missions


    //     return $actions;
    // }

    // public function structure(Request $request, Structure $structure)
    // {

    //     $actions =  [];

    //     if ($waiting_participations_count = $structure->participations()->where('participations.state', 'En attente de validation')->count()) {
    //         array_push($actions, [
    //             'type' => 'waiting_participations',
    //             'value' => $waiting_participations_count,
    //             'structure' => $structure,
    //         ]);
    //     }
    //     if ($outdated_missions_count = $structure->missions()->whereIn('state', ['Validée'])->outdated()->count()) {
    //         array_push($actions, [
    //             'type' => 'outdated_missions',
    //             'value' => $outdated_missions_count,
    //             'structure' => $structure,
    //         ]);
    //     }

    //     return $actions;
    // }
}
