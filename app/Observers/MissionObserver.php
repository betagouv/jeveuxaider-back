<?php

namespace App\Observers;

use App\Jobs\AirtableDeleteObject;
use App\Jobs\AirtableSyncObject;
use App\Jobs\DeclineWaitingParticipationsFromMission;
use App\Jobs\MissionGetQPV;
use App\Jobs\RuleDispatcherByEvent;
use App\Jobs\SendinblueSyncUser;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Notifications\MissionBeingProcessed;
use App\Notifications\MissionDeactivated;
use App\Notifications\MissionReactivated;
use App\Notifications\MissionSignaled;
use App\Notifications\MissionSubmitted;
use App\Notifications\MissionValidated;
use App\Notifications\MissionWaitingValidation;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MissionObserver
{
    /**
     * Listen to the Mission created event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function created(Mission $mission)
    {
        if ($mission->state == 'En attente de validation') {
            if ($mission->responsable) {
                $mission->responsable->notify(new MissionWaitingValidation($mission));
            }
            if ($mission->department) {
                Profile::where('notification__referent_frequency', 'realtime')
                ->whereHas('user.departmentsAsReferent', function (Builder $query) use ($mission) {
                    $query->where('number', $mission->department);
                })->get()->map(function ($profile) use ($mission) {
                    $profile->notify(new MissionSubmitted($mission));
                });
            }
        }

        if ($mission->state == 'Validée') {
            if ($mission->responsable) {
                $mission->responsable->notify(new MissionValidated($mission));
            }
            if ($mission->structure->shouldBeSearchable()) {
                $mission->structure->searchable();
            }
        }

        // Sync SENDINBLUE
        if (config('services.sendinblue.sync')) {
            $mission->structure->members->each(function ($user, $key) {
                SendinblueSyncUser::dispatch($user);
            });
        }

        // Sync Airtable
        if (config('services.airtable.sync')) {
            AirtableSyncObject::dispatch($mission);
            AirtableSyncObject::dispatch($mission->structure);
        }

        // Sync QPV
        if (config('services.qpv.sync')) {
            MissionGetQPV::dispatch($mission);
        }
    }

    /**
     * Listen to the Mission updated event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function updated(Mission $mission)
    {
        $user = Auth::guard('api')->user();

        $oldState = $mission->getOriginal('state');
        $newState = $mission->state;

        $mission->loadMissing(['structure', 'responsable']);

        // STATUT BROUILLON -> EN ATTENTE DE VALIDATION
        if($oldState == 'Brouillon' && $newState == 'En attente de validation') {
            if ($mission->responsable) {
                $mission->responsable->notify(new MissionWaitingValidation($mission));
            }
            if ($mission->department) {
                Profile::where('notification__referent_frequency', 'realtime')
                ->whereHas('user.departmentsAsReferent', function (Builder $query) use ($mission) {
                    $query->where('number', $mission->department);
                })->get()->map(function ($profile) use ($mission) {
                    $profile->notify(new MissionSubmitted($mission));
                });
            }
        }

        // AUTRES CHANGEMENTS DE STATUT
        if ($oldState != $newState) {
            switch ($newState) {
                case 'Validée':
                    if ($mission->responsable) {
                        $mission->responsable->notify(new MissionValidated($mission));
                    }
                    break;
                case 'Signalée':
                    if ($mission->responsable) {
                        $mission->responsable->notify(new MissionSignaled($mission));
                    }
                    // @TODO: Job CancelWaitingParticipationsFromMission (avec contexte mission signalée)
                    // Notif ON
                    $mission->loadMissing(['participations']);
                    $mission->participations->whereIn('state', ['En attente de validation', 'En cours de traitement'])
                        ->each(function ($participation) {
                            $participation->update(['state' => 'Annulée']);
                        });
                    // Notif OFF
                    $mission->participations()->update(['state' => 'Annulée']);
                    break;
                case 'Annulée':
                    // @TODO: Job CancelWaitingParticipationsFromMission (avec contexte mission annulée)
                    $mission->loadMissing(['participations']);
                    $mission->participations->whereIn('state', ['En attente de validation', 'En cours de traitement'])
                        ->each(function ($participation) {
                            $participation->update(['state' => 'Annulée']);
                        });
                    break;
                case 'Terminée':
                    DeclineWaitingParticipationsFromMission::dispatch($mission, 'mission_terminated');
                    $mission->sendNotificationsTemoignages();
                    break;
                case 'En cours de traitement':
                    if ($mission->responsable) {
                        $mission->responsable->notify(new MissionBeingProcessed($mission));
                    }
                    break;
            }

            if ($mission->structure->shouldBeSearchable()) {
                $mission->structure->searchable();
            }
        }

        // Transfert des conversations.
        if ($mission->getOriginal('responsable_id') != $mission->responsable_id) {
            $oldResponsable = Profile::find($mission->getOriginal('responsable_id'))->user;
            $newResponsable = $mission->responsable->user;

            $participations = $mission->participations()->pluck('id')->toArray();
            $conversationsQuery = $oldResponsable->conversations()
                ->where('conversable_type', 'App\Models\Participation')
                ->whereIn('conversable_id', $participations);

            foreach ($conversationsQuery->get() as $conversation) {
                $conversation->users()->syncWithoutDetaching([$newResponsable->id]);
                $conversation->loadMissing('conversable');
                $participation = $conversation->conversable;
                if ($participation && !in_array($participation->state, ['En attente de validation', 'En cours de traitement'])) {
                    $newResponsable->conversations()->updateExistingPivot($conversation->id, [
                        'read_at' => Carbon::now(),
                    ]);
                }
            }
        }

        // Sync Airtable
        if (config('services.airtable.sync')) {
            AirtableSyncObject::dispatch($mission);
            AirtableSyncObject::dispatch($mission->structure);
        }

        // Sync QPV
        if (config('services.qpv.sync')) {
            MissionGetQPV::dispatch($mission);
        }

        RuleDispatcherByEvent::dispatch('mission_updated', $mission);

        // Le statut n'a pas changé, mais la mission a été mise en ligne ou hors ligne
        if ($mission->isClean('state') && $mission->isDirty('is_online')) {
            if ($mission->is_online) {
                $mission->responsable->notify(new MissionReactivated($mission));
            } else {
                $mission->responsable->notify(new MissionDeactivated($mission));
            }
        }
    }

    public function saving(Mission $mission)
    {
        $mission->loadMissing('structure');

        // Calcul Places Left
        $participations_validated = Participation::where('mission_id', $mission->id)->whereIn('state', Participation::ACTIVE_STATUS)->count();
        $places_left = $mission->participations_max - $participations_validated;
        $mission->places_left = $places_left < 0 ? 0 : $places_left;

        if ($mission->date_type == 'ponctual') {
            $mission->commitment__time_period = null;
            $mission->recurrent_description = null;
        }
        if ($mission->date_type == 'recurring') {
            $mission->dates = null;
            $mission->end_date = null;
        }
        if ($mission->commitment__duration) {
            $mission->setCommitmentTotal();
        }

        if ($mission->type !== 'Mission en présentiel') {
            $mission->is_autonomy = false;
        }
        if ($mission->type !== 'Mission en présentiel' || $mission->is_autonomy === false) {
            $mission->autonomy_zips = null;
            $mission->autonomy_precisions = null;
        }

        if ($mission->type === 'Mission à distance') {
            $mission->department = $mission->structure->department;
        }

        if ($mission->is_autonomy === true) {
            $mission->address = null;
            $mission->zip = null;
            $mission->city = null;
            $mission->latitude = null;
            $mission->longitude = null;
        }

        if($mission->isDirty('state')) {
            if($mission->state == 'Validée') {
                $mission->is_online = true;
            } elseif($mission->state == 'Terminée') {
                $mission->is_online = $mission->is_online;
            } else {
                $mission->is_online = false;
            }
        }

        if(!$mission->structure->state == 'Validée') {
            $mission->is_online = false;
        }
    }

    /**
     * Listen to the Mission deleting event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function deleting(Mission $mission)
    {
        // Sync SENDINBLUE
        if (config('services.sendinblue.sync')) {
            $mission->structure->members->each(function ($user, $key) {
                SendinblueSyncUser::dispatch($user);
            });
        }

        // Sync Airtable
        if (config('services.airtable.sync')) {
            AirtableDeleteObject::dispatch($mission);
            AirtableSyncObject::dispatch($mission->structure);
        }
    }
}
