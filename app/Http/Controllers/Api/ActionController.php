<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Participation;
use App\Models\Territoire;
use App\Services\Snu;
use App\Settings\GeneralSettings;
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
                $actions[] = [
                    'type' => 'mission_template_waiting_validation',
                    'value' => MissionTemplate::where('state', 'waiting')->count(),
                ];
                $actions[] = [
                    'type' => 'territoires_waiting_validation',
                    'value' => Territoire::where('state', 'waiting')->count(),
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
            case 'tete_de_reseau':
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
            case 'referent':
            case 'referent_regional':
                $actions[] = [
                    'type' => 'messages_unread',
                    'value' => $user->getUnreadConversationsCount()
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
        if($user->context_role == 'volontaire'){
            $actions[] = [
                'type' => 'profile_incomplete',
                'value' => $user->profile->missing_fields,
            ];
        }
        $actions[] = [
            'type' => 'search_missions',
            'value' => true
        ];

        return $actions;
    }

    public function snuWaitingActions(Request $request, GeneralSettings $settings)
    {
        $actions =  [];
        $user = $request->user();
        $snuService = new Snu();
        $email = $user->email;

        if(!$settings->snu_mig_active) {
            return;
        }

        $items = $snuService->getWaitingActionsFromEmail($email);

        if($items){
            if(isset($items['waitingValidation'])){
                $actions[] = [
                    'type' => 'snu_waiting_validation',
                    'value' => $items['waitingValidation'],
                    'href' => config('app.snu_api_url') . '/jeveuxaider/signin?email=' . $email . '&token=' . config('app.snu_api_token'),
                ];
            }
            if(isset($items['contractToBeSigned'])){
                $actions[] = [
                    'type' => 'snu_contract_to_be_signed',
                    'value' => $items['contractToBeSigned'],
                    'href' => config('app.snu_api_url') . '/jeveuxaider/signin?email=' . $email . '&token=' . config('app.snu_api_token'),
                ];
            }
            if(isset($items['contractToBeFilled'])){
                $actions[] = [
                    'type' => 'snu_contract_to_be_filled',
                    'value' => $items['contractToBeFilled'],
                    'href' => config('app.snu_api_url') . '/jeveuxaider/signin?email=' . $email . '&token=' . config('app.snu_api_token'),
                ];
            }
        }

        return $actions;
    }

}
