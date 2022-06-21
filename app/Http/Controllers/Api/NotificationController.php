<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Invitation;
use App\Models\Message;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\NotificationBenevole;
use App\Models\NotificationTemoignage;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\User;
use App\Notifications\DocumentSubmitted;
use App\Notifications\ExportReady;
use App\Notifications\InvitationSent;
use App\Notifications\MessageCreated;
use App\Notifications\MissionAlmostFull;
use App\Notifications\MissionBeingProcessed;
use App\Notifications\MissionInDraft;
use App\Notifications\MissionOutdated;
use App\Notifications\MissionSignaled;
use App\Notifications\MissionSubmitted;
use App\Notifications\MissionTemplateWaiting;
use App\Notifications\MissionValidated;
use App\Notifications\MissionWaitingValidation;
use App\Notifications\ModerateurDailyTodo;
use App\Notifications\NoNewMission;
use App\Notifications\NotificationTemoignageCreate;
use App\Notifications\NotificationToBenevole;
use App\Notifications\ParticipationBeingProcessed;
use App\Notifications\ParticipationBenevoleCanceled;
use App\Notifications\ParticipationCanceled;
use App\Notifications\ParticipationDeclined;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;
use App\Notifications\ReferentDailyTodo;
use App\Notifications\RegisterUserResponsable;
use App\Notifications\RegisterUserVolontaire;
use App\Notifications\ReseauNewLead;
use App\Notifications\ResetPassword;
use App\Notifications\ResponsableDailyTodo;
use App\Notifications\StructureAssociationValidated;
use App\Notifications\StructureBeingProcessed;
use App\Notifications\StructureCollectivityValidated;
use App\Notifications\StructureInDraft;
use App\Notifications\StructureSignaled;
use App\Notifications\StructureSubmitted;
use App\Notifications\StructureValidated;
use App\Notifications\UserAnonymize;

class NotificationController extends Controller
{
    public function show(Request $request, $key)
    {
        $user = User::with('profile')->find($request->user()->id);
        $structure = Structure::latest()->first();
        $mission = Mission::latest()->first();
        $participation = Participation::latest()->first();

        switch ($key) {
            case 'benevole_register':
                $notification = new RegisterUserVolontaire($user);
                break;
            case 'responsable_register':
                $notification = new RegisterUserResponsable($structure);
                break;
            case 'responsable_still_in_draft':
                    $notification = new StructureInDraft($structure, 'j+1');
                break;
            case 'responsable_participation_created':
                $notification = new ParticipationWaitingValidation($participation);
                break;
            case 'benevole_participation_being_processed':
                $notification = new ParticipationBeingProcessed($participation);
                break;
            case 'benevole_participation_validated':
                $notification = new ParticipationValidated($participation);
                break;
            case 'benevole_participation_temoignage':
                $notificationTemoignage = NotificationTemoignage::latest()->first();
                $notification = new NotificationTemoignageCreate($notificationTemoignage);
                break;
            case 'benevole_participation_canceled':
                $notification = new ParticipationCanceled($participation);
                break;
            case 'responsable_participation_canceled':
                $notification = new ParticipationBenevoleCanceled($participation, "not_available");
                break;
            case 'benevole_participation_refused':
                $notification = new ParticipationDeclined($participation, "requirements_not_fulfilled");
                break;
            case 'responsable_mission_created':
                $notification = new MissionWaitingValidation($mission);
                break;
            case 'responsable_mission_validated':
                $notification = new MissionValidated($mission);
                break;
            case 'responsable_mission_being_processed':
                $notification = new MissionBeingProcessed($mission);
                break;
            case 'responsable_mission_outdated':
                $notification = new MissionOutdated($mission);
                break;
            case 'responsable_mission_signaled':
                $notification = new MissionSignaled($mission);
                break;
            case 'responsable_mission_almost_full':
                $notification = new MissionAlmostFull($mission);
                break;
            case 'responsable_missin_in_draft':
                $notification = new MissionInDraft($mission);
                break;
            case 'referent_mission_created':
                $notification = new MissionSubmitted($mission);
                break;
            case 'referent_organisation_created':
                $notification = new StructureSubmitted($structure);
                break;
            case 'responsable_organisation_being_processed':
                $notification = new StructureBeingProcessed($structure);
                break;
            case 'responsable_association_validated':
                $notification = new StructureAssociationValidated($structure);
                break;
            case 'responsable_collectivite_validated':
                $notification = new StructureCollectivityValidated($structure);
                break;
            case 'responsable_organisation_signaled':
                $notification = new StructureSignaled($structure);
                break;
            case 'responsable_organisation_validated':
                $notification = new StructureValidated($structure);
                break;
            case 'admin_reseau_new_lead':
                $form = [
                    'name' => 'Banque alimentaires',
                    'nb_antennes' => 34,
                    'nb_employees' => 45,
                    'nb_volunteers' => 4000,
                    'first_name' => 'Prénom',
                    'last_name' => 'Nom',
                    'email' => 'email@test.fr',
                    'phone' => '06 12 23 45 67 89',
                    'description' => 'Description de ma demande'
                ];
                $notification = new ReseauNewLead($form);
                break;
            case 'admin_document_submitted':
                $document = Document::latest()->first();
                $notification = new DocumentSubmitted($document);
                break;
            case 'export_ready':
                $notification = new ExportReady($user, 'lien-du-fichier');
                break;
            case 'invitation_sent':
                $inviation = Invitation::latest()->first();
                $notification = new InvitationSent($inviation);
                break;
            case 'new_message':
                $message = Message::whereHas('from')->whereHas('conversation.conversable')->latest()->first();
                $notification = new MessageCreated($message);
                break;
            case 'mission_template_created':
                $missionTemplate = MissionTemplate::whereHas('reseau')->latest()->first();
                $notification = new MissionTemplateWaiting($missionTemplate);
                break;
            // case 'moderateur_daily_todo':
            //     $byDepartment[75] = [
            //         'department_name' => 'Paris',
            //         'missions' => ['test'],
            //         'structures' => ['test'],
            //         'referents' => [[
            //             'first_name' => 'Prénom',
            //             'last_name' => 'Nom',
            //             'email' => 'test@test.fr',
            //             'mobile' => '06 12 34 56 78',
            //         ]],
            //     ];
            //     $notification = new ModerateurDailyTodo($byDepartment);
            //     break;
            case 'responsable_no_new_mission':
                $notification = new NoNewMission($structure);
                break;
            case 'benevole_marketplace_mission':
                $notificationBenevole = NotificationBenevole::latest()->first();
                $notification = new NotificationToBenevole($notificationBenevole);
                break;
            case 'referent_waiting_actions':
                $notification = new ReferentDailyTodo([1,2], [1,2]);
                break;
            case 'reset_password':
                $notification = new ResetPassword('token');
                break;
            case 'responsable_waiting_actions':
                $notification = new ResponsableDailyTodo([1,2,3]);
                break;
            case 'user_anonymize':
                $notification = new UserAnonymize();
                break;
        }

        return isset($notification) ? $notification->toMail($user)->render() : abort(401, 'Notification introuvable');
    }
}
