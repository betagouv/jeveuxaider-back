<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\Territoire;
use App\Services\Snu;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserActionController extends Controller
{
    public function index(Request $request)
    {
        $actions = [];
        $user = $request->user();

        switch ($request->header('Context-Role')) {
            case 'admin':
                $actions[] = [
                    'type' => 'organisations_waiting_validation',
                    'value' => Structure::where('state', 'En attente de validation')->count(),
                ];
                $actions[] = [
                    'type' => 'organisations_in_progress',
                    'value' => Structure::where('state', 'En cours de traitement')->count(),
                ];
                $actions[] = [
                    'type' => 'missions_waiting_validation',
                    'value' => Mission::where('state', 'En attente de validation')->count(),
                ];
                $actions[] = [
                    'type' => 'missions_in_progress',
                    'value' => Mission::where('state', 'En cours de traitement')->count(),
                ];
                $actions[] = [
                    'type' => 'missions_outdated',
                    'value' => Mission::where('end_date', '<', Carbon::now())->where('state', 'Validée')->count(),
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
                    'value' => Structure::role($request->header('Context-Role'))->where('state', 'Validée')->count() && ! Mission::role($request->header('Context-Role'))->count() ? true : false,
                ];
                $actions[] = [
                    'type' => 'messages_unread',
                    'value' => $user->getUnreadConversationsCount(),
                ];
                $actions[] = [
                    'type' => 'participations_waiting_validation',
                    'value' => Participation::role($request->header('Context-Role'))->ofResponsable($user->profile->id)->where('state', 'En attente de validation')->count(),
                ];
                $actions[] = [
                    'type' => 'participations_in_progress',
                    'value' => Participation::role($request->header('Context-Role'))->ofResponsable($user->profile->id)->where('state', 'En cours de traitement')->count(),
                ];
                $actions[] = [
                    'type' => 'participations_need_to_be_treated',
                    'value' => Participation::role($request->header('Context-Role'))->ofResponsable($user->profile->id)->needToBeTreated($user->profile->id)->count(),
                ];
                $actions[] = [
                    'type' => 'missions_outdated',
                    'value' => Mission::role($request->header('Context-Role'))->where('end_date', '<', Carbon::now())->where('state', 'Validée')->count(),
                ];
                if ($user->contextable_id) {
                    $structure = Structure::find($user->contextable_id);
                    if ($structure) {
                        if ($structure->state == 'Signalée') {
                            $actions[] = [
                                'type' => 'organisation_signaled',
                                'value' => true,
                                'href' => 'https://reserve-civique.crisp.help/fr/article/mon-organisation-ou-ma-mission-a-ete-signalee-quest-ce-que-cela-signifie-r71xm2/',
                            ];
                        }
                        $actions[] = [
                            'type' => $structure->state == 'Brouillon' ? 'organisation_brouillon_incomplete' : 'organisation_incomplete',
                            'value' => $structure->missing_fields,
                        ];
                    }
                }
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
                $actions[] = [
                    'type' => 'mission_template_new',
                    'value' => MissionTemplate::role($request->header('Context-Role'))->count() == 0 ? true : false,
                ];
                $actions[] = [
                    'type' => 'mission_template_manage',
                    'value' => MissionTemplate::role($request->header('Context-Role'))->count() > 0 ? true : false,
                ];
                break;
            case 'referent':
            case 'referent_regional':
                $actions[] = [
                    'type' => 'messages_unread',
                    'value' => $user->getUnreadConversationsCount(),
                ];
                $actions[] = [
                    'type' => 'organisations_waiting_validation',
                    'value' => Structure::role($request->header('Context-Role'))->where('state', 'En attente de validation')->count(),
                ];
                $actions[] = [
                    'type' => 'organisations_in_progress',
                    'value' => Structure::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->count(),
                ];
                $actions[] = [
                    'type' => 'missions_waiting_validation',
                    'value' => Mission::role($request->header('Context-Role'))->where('state', 'En attente de validation')->count(),
                ];
                $actions[] = [
                    'type' => 'missions_in_progress',
                    'value' => Mission::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->count(),
                ];
                break;
        }

        return $actions;
    }

    public function benevole(Request $request)
    {
        $actions = [];
        $user = $request->user();

        $actions[] = [
            'type' => 'messages_unread',
            'value' => $user->getUnreadConversationsCount(),
        ];
        if ($user->context_role == 'volontaire') {
            $actions[] = [
                'type' => 'profile_incomplete',
                'value' => $user->profile->missing_fields,
                'completion_rate' => $user->profile->completion_rate
            ];
            $actions[] = [
                'type' => 'profile_without_activities',
                'value' => $user->profile->activities->count() > 0 ? false : true,
            ];
        }
        $actions[] = [
            'type' => 'search_missions',
            'value' => true,
        ];

        return $actions;
    }

    public function snuWaitingActions(Request $request, GeneralSettings $settings)
    {
        $actions = [];
        $user = $request->user();
        $snuService = new Snu();
        $email = $user->email;

        if (! $settings->snu_mig_active) {
            return [];
        }

        $tokenJva = $snuService->getTokenJvaByEmail($email);

        if(!$tokenJva){
            return [];
        }

        $items = $snuService->getWaitingActionsFromEmail($email);

        if ($items) {
            if (isset($items['waitingValidation'])) {
                $actions[] = [
                    'type' => 'snu_application_waiting_validation',
                    'value' => $items['waitingValidation'],
                    'href' => config('app.snu_api_url').'/jeveuxaider/signin?token_jva='.$tokenJva,
                ];
            }
            if (isset($items['applicationWaitingValidation'])) {
                $actions[] = [
                    'type' => 'snu_application_waiting_validation',
                    'value' => $items['applicationWaitingValidation'],
                    'href' => config('app.snu_api_url').'/jeveuxaider/signin?token_jva='.$tokenJva,
                ];
            }
            if (isset($items['contractToBeSigned'])) {
                $actions[] = [
                    'type' => 'snu_contract_to_be_signed',
                    'value' => $items['contractToBeSigned'],
                    'href' => config('app.snu_api_url').'/jeveuxaider/signin?token_jva='.$tokenJva,
                ];
            }
            if (isset($items['contractToBeFilled'])) {
                $actions[] = [
                    'type' => 'snu_contract_to_be_filled',
                    'value' => $items['contractToBeFilled'],
                    'href' => config('app.snu_api_url').'/jeveuxaider/signin?token_jva='.$tokenJva,
                ];
            }
            if (isset($items['missionWaitingCorrection'])) {
                $actions[] = [
                    'type' => 'snu_mission_waiting_correction',
                    'value' => $items['missionWaitingCorrection'],
                    'href' => config('app.snu_api_url').'/jeveuxaider/signin?token_jva='.$tokenJva,
                ];
            }
            if (isset($items['missionInProgress'])) {
                $actions[] = [
                    'type' => 'snu_mission_in_progress',
                    'value' => $items['missionInProgress'],
                    'href' => config('app.snu_api_url').'/jeveuxaider/signin?token_jva='.$tokenJva,
                ];
            }
            if (isset($items['volunteerToHost'])) {
                $actions[] = [
                    'type' => 'snu_volunteer_to_host',
                    'value' => $items['volunteerToHost'],
                    'href' => config('app.snu_api_url').'/jeveuxaider/signin?token_jva='.$tokenJva,
                ];
            }
        }

        return $actions;
    }
}
