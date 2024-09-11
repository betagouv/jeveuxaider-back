<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Invitation;
use App\Models\Message;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Note;
use App\Models\NotificationBenevole;
use App\Models\NotificationTemoignage;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\User;
use App\Models\UserArchivedDatas;
use App\Notifications\BenevoleCejNoParticipation;
use App\Notifications\BenevoleCejOneYearAfter;
use App\Notifications\BenevoleCejSixMonthsAfter;
use App\Notifications\BenevoleFTNoParticipationJ10;
use App\Notifications\BenevoleFTNoParticipationJ3;
use App\Notifications\BenevoleIsInactive;
use App\Notifications\BenevoleIsInactiveSecondReminder;
use App\Notifications\BenevoleWillBeArchived;
use App\Notifications\BenevoleWillBeArchivedSecondReminder;
use App\Notifications\DocumentSubmitted;
use App\Notifications\ExportReady;
use App\Notifications\InvitationSent;
use App\Notifications\MessageMissionCreated;
use App\Notifications\MessageParticipationCreated;
use App\Notifications\MessageStructureCreated;
use App\Notifications\MissionAlmostFull;
use App\Notifications\MissionBeingProcessed;
use App\Notifications\MissionDeactivated;
use App\Notifications\MissionFull;
use App\Notifications\MissionStillInDraft;
use App\Notifications\MissionOutdatedFirstReminder;
use App\Notifications\MissionOutdatedSecondReminder;
use App\Notifications\MissionReactivated;
use App\Notifications\MissionShared;
use App\Notifications\MissionSignaled;
use App\Notifications\MissionSubmitted;
use App\Notifications\MissionTemplateWaiting;
use App\Notifications\MissionValidated;
use App\Notifications\MissionWaitingValidation;
use App\Notifications\NoNewMission;
use App\Notifications\NoteCreated;
use App\Notifications\NotificationTemoignageCreate;
use App\Notifications\NotificationToBenevole;
use App\Notifications\ParticipationBeingProcessed;
use App\Notifications\ParticipationBenevoleCanceled;
use App\Notifications\ParticipationBenevoleValidated;
use App\Notifications\ParticipationCanceled;
use App\Notifications\ParticipationCreated;
use App\Notifications\ParticipationCreatedFTAdviser;
use App\Notifications\ParticipationDeclined;
use App\Notifications\ParticipationDeclinedFromMissionTerminated;
use App\Notifications\ParticipationShouldBeDone;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationValidatedCejAdviser;
use App\Notifications\ParticipationWaitingValidation;
use App\Notifications\ParticipationWillStart;
use App\Notifications\ReferentDailyTodo;
use App\Notifications\ReferentSummaryDaily;
use App\Notifications\ReferentSummaryMonthly;
use App\Notifications\StructureWaitingValidation;
use App\Notifications\RegisterUserVolontaire;
use App\Notifications\RegisterUserVolontaireCej;
use App\Notifications\RegisterUserVolontaireCejAdviser;
use App\Notifications\RegisterUserVolontaireFTAdviser;
use App\Notifications\ReseauNewLead;
use App\Notifications\ResetPassword;
use App\Notifications\ResponsableIsInactive;
use App\Notifications\ResponsableIsInactiveSecondReminder;
use App\Notifications\ResponsableMissionsDeactivated;
use App\Notifications\ResponsableMissionsReactivated;
use App\Notifications\ResponsableParticipationAModeredEnPriorite;
use App\Notifications\ResponsableSummaryDaily;
use App\Notifications\ResponsableSummaryMonthly;
use App\Notifications\ResponsableWillBeArchived;
use App\Notifications\ResponsableWillBeArchivedSecondReminder;
use App\Notifications\SendCodeUnarchiveUserDatas;
use App\Notifications\StructureAskUnregister;
use App\Notifications\StructureAssociationValidated;
use App\Notifications\StructureBeingProcessed;
use App\Notifications\StructureCollectivityValidated;
use App\Notifications\StructureInDraft;
use App\Notifications\StructureWithoutMissionFirstReminder;
use App\Notifications\StructureSignaled;
use App\Notifications\StructureSubmitted;
use App\Notifications\StructureSwitchResponsable;
use App\Notifications\StructureUnsubscribed;
use App\Notifications\StructureValidated;
use App\Notifications\StructureWithoutMissionSecondReminder;
use App\Notifications\UserAnonymize;
use App\Notifications\UserBannedInappropriateBehavior;
use App\Notifications\UserBannedNotRegularResident;
use App\Notifications\UserBannedYoungerThan16;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class NotificationController extends Controller
{
    public function show(Request $request, $key)
    {
        $user = User::with('profile')->find($request->user()->id);
        $userOrProfile = $this->getRecepientResolver($key, $user);
        $notification = $this->getNotification($key, $user);

        if(isset($notification)) {
            return $notification->toMail($userOrProfile)->render();
        }

        return abort(401, 'Notification introuvable');
    }

    public function test(Request $request, $key)
    {
        $user = User::with('profile')->find($request->user()->id);
        $userOrProfile = $this->getRecepientResolver($key, $user);
        $notification = $this->getNotification($key, $user);

        if(isset($notification)) {
            return $userOrProfile->notify($notification);
        }

        return abort(401, 'Notification introuvable');
    }

    private function getRecepientResolver($key, $user)
    {

        switch ($key) {
            case 'benevole_cej_no_participation':
            case 'benevole_cej_one_year_after':
            case 'benevole_cej_six_months_after':
            case 'admin_document_submitted':
            case 'responsable_mission_outdated':
            case 'referent_mission_created':
            case 'register_user_volontaire_cej':
            case 'responsable_waiting_actions':
            case 'responsable_association_validated':
            case 'responsable_collectivite_validated':
            case 'referent_organisation_created':
            case 'structure_switch_responsable':
            case 'benevole_participation_should_be_done':
            case 'benevole_participation_will_start':
            case 'user_no_participation_ft_j3':
            case 'user_no_participation_ft_j10':
                return $user->profile;
            default:
                return $user;
        }
    }

    private function getNotification($key, $user)
    {

        $structure = Structure::latest()->first();
        $mission = Mission::latest()->first();
        $participation = Participation::latest()->first();

        switch ($key) {
            case 'benevole_register':
                $notification = new RegisterUserVolontaire($user);
                break;
            case 'responsable_organisation_waiting_validation':
                $notification = new StructureWaitingValidation($structure);
                break;
            case 'responsable_still_in_draft':
                $notification = new StructureInDraft($structure, 'j+1');
                break;
            case 'responsable_participation_created':
                $notification = new ParticipationWaitingValidation($participation);
                break;
            case 'benevole_participation_created':
                $notification = new ParticipationCreated($participation);
                break;
            case 'benevole_participation_being_processed':
                $notification = new ParticipationBeingProcessed($participation);
                break;
            case 'benevole_participation_should_be_done':
                $notification = new ParticipationShouldBeDone($participation);
                break;
            case 'benevole_participation_will_start':
                $notification = new ParticipationWillStart($participation);
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
                $notification = new ParticipationBenevoleCanceled($participation, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer maximus neque nec nulla ullamcorper auctor. Aliquam in leo massa. Etiam luctus luctus volutpat. Curabitur interdum sem a urna finibus, ut porttitor ipsum elementum. Aliquam erat volutpat. Integer ultrices, metus id sagittis scelerisque, lectus ex feugiat massa, at laoreet ante enim ac ipsum.', 'not_available');
                break;
            case 'responsable_participation_validated_by_benevole':
                $notification = new ParticipationBenevoleValidated($participation);
                break;
            case 'benevole_participation_refused':
                $notification = new ParticipationDeclined($participation, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer maximus neque nec nulla ullamcorper auctor. Aliquam in leo massa. Etiam luctus luctus volutpat. Curabitur interdum sem a urna finibus, ut porttitor ipsum elementum. Aliquam erat volutpat. Integer ultrices, metus id sagittis scelerisque, lectus ex feugiat massa, at laoreet ante enim ac ipsum.', 'requirements_not_fulfilled');
                break;
            case 'benevole_participation_declined_mission_terminated':
                $notification = new ParticipationDeclinedFromMissionTerminated($participation);
                break;
            case 'benevole_mission_shared':
                $notification = new MissionShared($mission, $user);
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
            case 'responsable_mission_outdated_first_reminder':
                $notification = new MissionOutdatedFirstReminder($mission);
                break;
            case 'responsable_mission_outdated_second_reminder':
                $notification = new MissionOutdatedSecondReminder($mission);
                break;
            case 'responsable_mission_signaled':
                $notification = new MissionSignaled($mission);
                break;
            case 'responsable_mission_almost_full':
                $notification = new MissionAlmostFull($mission);
                break;
            case 'responsable_mission_full':
                $notification = new MissionFull($mission);
                break;
            case 'responsable_mission_still_in_draft':
                $notification = new MissionStillInDraft($mission);
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
            case 'responsable_structure_unsubscribed':
                $notification = new StructureUnsubscribed($structure);
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
            case 'responsable_organisation_without_mission_first_reminder':
                $notification = new StructureWithoutMissionFirstReminder($structure);
                break;
            case 'responsable_organisation_without_mission_second_reminder':
                $notification = new StructureWithoutMissionSecondReminder($structure);
                break;
            case 'user_unarchive_code':
                $userArchivedDatas = new UserArchivedDatas();
                $userArchivedDatas->email = 'test@test.fr';
                $userArchivedDatas->code = random_int(100000, 999999);
                $userArchivedDatas->datas = 'test@test.fr';
                $notification = new SendCodeUnarchiveUserDatas($userArchivedDatas);
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
                    'description' => 'Description de ma demande',
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
            case 'benevole_message_participation':
                $message = Message::whereHas('from.roles', function (Builder $query) {
                    $query->where('roles.id', 2);
                })->whereHas('conversation', function (Builder $query) {
                    $query->where('conversable_type', 'App\\Models\\Participation');
                })->latest()->first();
                $notification = new MessageParticipationCreated($message);
                break;
            case 'responsable_message_participation':
                $message = Message::whereDoesntHave('from.roles')->whereHas('conversation', function (Builder $query) {
                    $query->where('conversable_type', 'App\\Models\\Participation');
                })->latest()->first();
                $notification = new MessageParticipationCreated($message);
                break;
            case 'responsable_message_organisation':
                $message = Message::whereHas('from.roles', function (Builder $query) {
                    $query->where('roles.id', 3);
                })->whereHas('conversation', function (Builder $query) {
                    $query->where('conversable_type', 'App\\Models\\Structure');
                })->latest()->first();
                $notification = new MessageStructureCreated($message);
                break;
            case 'referent_message_organisation':
                $message = Message::whereHas('from.roles', function (Builder $query) {
                    $query->where('roles.id', '!=', 3);
                })->whereHas('conversation', function (Builder $query) {
                    $query->where('conversable_type', 'App\\Models\\Structure');
                })->latest()->first();
                $notification = new MessageStructureCreated($message);
                break;
            case 'responsable_message_mission':
                $message = Message::whereHas('from.roles', function (Builder $query) {
                    $query->where('roles.id', 3);
                })->whereHas('conversation', function (Builder $query) {
                    $query->where('conversable_type', 'App\\Models\\Mission');
                })->latest()->first();
                $notification = new MessageMissionCreated($message);
                break;
            case 'referent_message_mission':
                $message = Message::whereHas('from.roles', function (Builder $query) {
                    $query->where('roles.id', '!=', 3);
                })->whereHas('conversation', function (Builder $query) {
                    $query->where('conversable_type', 'App\\Models\\Mission');
                })->latest()->first();
                $notification = new MessageMissionCreated($message);
                break;
            case 'mission_template_created':
                $missionTemplate = MissionTemplate::whereHas('reseau')->latest()->first();
                $notification = new MissionTemplateWaiting($missionTemplate);
                break;
            case 'responsable_no_new_mission':
                $notification = new NoNewMission($structure);
                break;
            case 'benevole_marketplace_mission':
                $notificationBenevole = NotificationBenevole::latest()->first();
                $notification = new NotificationToBenevole($notificationBenevole);
                break;
            case 'referent_waiting_actions':
                $notification = new ReferentDailyTodo([1, 2], [1, 2]);
                break;
            case 'reset_password':
                $notification = new ResetPassword('token');
                break;
            case 'responsable_participations_need_to_be_treated':
                $notification = new ResponsableParticipationAModeredEnPriorite(45, 20, 25);
                break;
            case 'user_anonymize':
                $notification = new UserAnonymize();
                break;
            case 'benevole_cej_no_participation':
                $notification = new BenevoleCejNoParticipation($user->profile);
                break;
            case 'participation_validated_cej_adviser':
                $notification = new ParticipationValidatedCejAdviser($participation);
                break;
            case 'participation_created_ft_adviser':
                $notification = new ParticipationCreatedFTAdviser($participation);
                break;
            case 'register_user_volontaire_cej':
                $notification = new RegisterUserVolontaireCej($user);
                break;
            case 'register_user_volontaire_cej_adviser':
                $notification = new RegisterUserVolontaireCejAdviser($user->profile);
                break;
            case 'register_user_volontaire_ft_adviser':
                $notification = new RegisterUserVolontaireFTAdviser($user->profile);
                break;
            case 'benevole_cej_one_year_after':
                $notification = new BenevoleCejOneYearAfter($user->profile);
                break;
            case 'benevole_cej_six_months_after':
                $notification = new BenevoleCejSixMonthsAfter($user->profile);
                break;
            case 'notes_created':
                $note = Note::latest()->first();
                $notification = new NoteCreated($note);
                break;
            case 'structure_switch_responsable':
                $notification = new StructureSwitchResponsable($structure, $user->profile);
                break;
            case 'structure_unregister_contact_admin':
                $notification = new StructureAskUnregister($user, $structure);
                break;
            case 'responsable_summary_daily':
                $profile = Profile::select('id', 'email')
                    ->whereHas('user.structures')
                    ->whereHas('missions.participations')
                    ->whereHas('user.roles', function (Builder $query) {
                        $query->where('roles.id', 2);
                    })
                    ->where('notification__responsable_frequency', 'summary')
                    ->latest()->first();
                $notification = new ResponsableSummaryDaily($user->profile->id);
                break;
            case 'responsable_summary_monthly':
                $profile = Profile::select('id', 'email')
                    ->where('notification__responsable_bilan', true)
                    ->whereHas('user.structures')
                    ->whereHas('user.structures.participations')
                    ->whereHas('user.roles', function (Builder $query) {
                        $query->where('roles.id', 2);
                    })
                    ->latest()->first();
                $notification = new ResponsableSummaryMonthly($user->profile->id);
                break;
            case 'referent_summary_daily':
                $profile = Profile::select('id', 'email')
                    ->whereHas('user.roles', function (Builder $query) {
                        $query->where('roles.id', 3);
                    })
                    ->where('notification__referent_frequency', 'summary')
                    ->latest()->first();
                $notification = new ReferentSummaryDaily($user->profile->id);
                break;
            case 'referent_summary_monthly':
                $profile = Profile::select('id', 'email')
                    ->whereHas('user.roles', function (Builder $query) {
                        $query->where('roles.id', 3);
                    })
                    ->where('notification__referent_bilan', true)
                    ->latest()->first();
                $notification = new ReferentSummaryMonthly($user->profile->id);
                break;
            case 'responsable_mission_deactivated':
                $notification = new MissionDeactivated($mission);
                break;
            case 'responsable_mission_reactivated':
                $notification = new MissionReactivated($mission);
                break;
            case 'responsable_missions_deactivated':
                $notification = new ResponsableMissionsDeactivated();
                break;
            case 'responsable_missions_reactivated':
                $notification = new ResponsableMissionsReactivated();
                break;
            case 'user_banned_not_regular_resident':
                $notification = new UserBannedNotRegularResident();
                break;
            case 'user_banned_younger_than_16':
                $notification = new UserBannedYoungerThan16();
                break;
            case 'user_banned_inappropriate_behavior':
                $notification = new UserBannedInappropriateBehavior();
                break;
            case 'benevole_will_be_archived':
                $notification = new BenevoleWillBeArchived();
                break;
            case 'benevole_will_be_archived_second_reminder':
                $notification = new BenevoleWillBeArchivedSecondReminder();
                break;
            case 'responsable_will_be_archived':
                $notification = new ResponsableWillBeArchived($structure);
                break;
            case 'responsable_will_be_archived_second_reminder':
                $notification = new ResponsableWillBeArchivedSecondReminder($structure);
                break;
            case 'responsable_is_inactive':
                $notification = new ResponsableIsInactive($structure);
                break;
            case 'responsable_is_inactive_second_reminder':
                $notification = new ResponsableIsInactiveSecondReminder($structure);
                break;
            case 'benevole_is_inactive':
                $notification = new BenevoleIsInactive($structure);
                break;
            case 'benevole_is_inactive_second_reminder':
                $notification = new BenevoleIsInactiveSecondReminder($structure);
                break;
            case 'user_no_participation_ft_j3':
                $notification = new BenevoleFTNoParticipationJ3($user->profile);
                break;
            case 'user_no_participation_ft_j10':
                $notification = new BenevoleFTNoParticipationJ10($user->profile);
                break;
            default:
                return null;
        }

        return $notification;
    }
}
