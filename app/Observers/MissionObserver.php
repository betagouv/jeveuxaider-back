<?php

namespace App\Observers;

use App\Jobs\AirtableDeleteObject;
use App\Jobs\AirtableSyncObject;
use App\Jobs\MissionGetQPV;
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
        $oldState = $mission->getOriginal('state');
        $newState = $mission->state;

        $mission->load(['structure', 'responsable']);

        if ($oldState != $newState) {
            switch ($newState) {
                case 'Validée':
                    if ($mission->responsable) {
                        $mission->responsable->notify(new MissionValidated($mission));
                    }
                    break;
                case 'En attente de validation':
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
                    break;
                case 'Signalée':
                    if ($mission->responsable) {
                        $mission->responsable->notify(new MissionSignaled($mission));
                        // Notif ON
                        foreach ($mission->participations->whereIn('state', ['En attente de validation', 'En cours de traitement']) as $participation) {
                            $participation->update(['state' => 'Annulée']);
                        }
                        // Notif OFF
                        $mission->participations()->update(['state' => 'Annulée']);
                    }
                    break;
                case 'Annulée':
                    if ($mission->responsable) {
                        foreach (Participation::where('mission_id', $mission->id)->whereIn('state', ['En attente de validation', 'En cours de traitement']) as $participation) {
                            $participation->update(['state' => 'Annulée']);
                        }
                    }
                    break;
                case 'Terminée':
                    if ($mission->responsable) {
                        // Notif OFF
                        $mission->participations->whereIn('state', ['En attente de validation', 'En cours de traitement'])
                            ->each(function ($participation) {
                                activity()
                                    ->performedOn($participation)
                                    ->withProperties([
                                            'attributes' => ['state' => 'Annulée'],
                                            'old' => ['state' => $participation->state]
                                        ])
                                    ->event('updated')
                                    ->log('updated');

                                $participation->state = 'Annulée';
                                $participation->saveQuietly();
                            });

                        // Notifications temoignage.
                        $mission->sendNotificationsTemoignages();
                    }
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
            $conversationsQuery = $oldResponsable->conversations()->whereIn('conversable_id', $participations);

            foreach ($conversationsQuery->get() as $conversation) {
                $conversation->users()->syncWithoutDetaching([$newResponsable->id]);
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

        if ($mission->getOriginal('is_active') != $mission->is_active) {
            if ($mission->is_active) {
                $mission->responsable->notify(new MissionReactivated($mission));
            } else {
                $mission->responsable->notify(new MissionDeactivated($mission));
            }
        }
    }

    public function saving(Mission $mission)
    {
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
            $mission->load('structure');
            $mission->department = $mission->structure->department;
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
