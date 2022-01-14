<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\Mission;
use App\Models\Participation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActionController extends Controller
{

    public function index(Request $request)
    {
        $actions =  [];
        $user = $request->user();

        switch ($request->header('Context-Role')) {
            case 'admin':
                $actions[] = [
                    'type' => 'organisations_waiting_validation',
                    'value' => Structure::where('state', 'En attente de validation')->count()
                ];
                $actions[] = [
                    'type' => 'missions_waiting_validation',
                    'value' => Mission::where('state', 'En attente de validation')->count(),
                ];
                break;
            case 'responsable':
                $actions[] = [
                    'type' => 'mission_new',
                    'value' => Mission::role($request->header('Context-Role'))->count() ? false : true,
                ];
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
                $actions[] = [
                    'type' => 'organisation_incomplete',
                    'value' => isset($user->profile->structures[0]) ? $user->profile->structures[0]->missing_fields : false,
                ];
                break;
            case 'referent':
            case 'referent_regional':
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
                    'type' => 'organisations_waiting_validation',
                    'value' => Structure::role($request->header('Context-Role'))->where('state', 'En attente de validation')->count()
                ];
                $actions[] = [
                    'type' => 'missions_waiting_validation',
                    'value' => Mission::role($request->header('Context-Role'))->where('state', 'En attente de validation')->count(),
                ];
                break;
        }

        return $actions;
    }

    public function benevole(Request $request)
    {
        $actions =  [];
        $user = $request->user();

        $actions[] = [
            'type' => 'messages_unread',
            'value' => $user->getUnreadConversationsCount()
        ];
        $actions[] = [
            'type' => 'profile_incomplete',
            'value' => $user->profile->missing_fields,
        ];
        $actions[] = [
            'type' => 'search_missions',
            'value' => true
        ];

        return $actions;
    }

}
