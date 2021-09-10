<?php

namespace App\Observers;

use App\Helpers\Utils;
use App\Jobs\SendinblueSyncUser;
use App\Models\Mission;
use App\Models\NotificationAvis;
use App\Models\Participation;
use App\Models\Profile;
use App\Notifications\NotificationAvisCreate;
use App\Notifications\MissionValidated;
use App\Notifications\MissionWaitingValidation;
use App\Notifications\MissionSignaled;
use App\Notifications\MissionSubmitted;
use Illuminate\Support\Str;

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
                Profile::where('referent_department', $mission->department)->get()->map(function ($profile) use ($mission) {
                    $profile->notify(new MissionSubmitted($mission));
                });
            }
        }

        if ($mission->state == 'Validée') {
            if ($mission->responsable) {
                $mission->responsable->notify(new MissionValidated($mission));
            }
        }

        // Maj Sendinblue
        if (config('app.env') === 'production') {
            $mission->structure->responsables->each(function ($profile, $key) {
                if ($profile->user) { // Parfois il n'y a pas de user car ce sont des profiles invités
                    SendinblueSyncUser::dispatch($profile->user);
                }
            });
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
                        Profile::where('referent_department', $mission->department)->get()->map(function ($profile) use ($mission) {
                            $profile->notify(new MissionSubmitted($mission));
                        });
                    }
                    break;
                case 'Signalée':
                    if ($mission->responsable) {
                        $mission->responsable->notify(new MissionSignaled($mission));
                        // Notif ON
                        foreach ($mission->participations->where("state", "En attente de validation") as $participation) {
                            $participation->update(['state' => 'Annulée']);
                        }
                        // Notif OFF
                        $mission->participations()->update(['state' => 'Annulée']);
                    }
                    break;
                case 'Annulée':
                    if ($mission->responsable) {
                        foreach ($mission->participations->where("state", "En attente de validation") as $participation) {
                            $participation->update(['state' => 'Annulée']);
                        }
                    }
                    break;
                case 'Terminée':
                    if ($mission->responsable) {
                        // Notif OFF
                        $mission->participations()->where("state", "En attente de validation")->update(['state' => 'Annulée']);

                        // Notifications avis.
                        $participations = $mission->participations()->where('state', 'Validée')->get();
                        foreach ($participations as $participation) {
                            // Be sure there is no existing notification.
                            if (NotificationAvis::where('participation_id', $participation->id)->exists()) {
                                continue;
                            }

                            do {
                                $token = Str::random(32);
                            } while (NotificationAvis::where('token', $token)->first());

                            $notificationAvis = NotificationAvis::create([
                                'token' => $token,
                                'participation_id' => $participation->id,
                                'reminders_sent' => 1,
                            ]);
                            $notificationAvis->participation->profile->user->notify(new NotificationAvisCreate($notificationAvis));
                        }
                    }
                    break;
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
    }

    public function saving(Mission $mission)
    {
        // Calcul Places Left
        $places_left = $mission->participations_max - $mission->participations->whereIn('state', Participation::ACTIVE_STATUS)->count();
        $mission->places_left = $places_left < 0 ? 0 : $places_left;

        if ($mission->commitment__duration) {
            $mission->setCommitmentTotal();
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
        // Maj Sendinblue
        if (config('app.env') === 'production') {
            $mission->structure->responsables->each(function ($profile, $key) {
                if ($profile->user) { // Parfois il n'y a pas de user car ce sont des profiles invités
                    SendinblueSyncUser::dispatch($profile->user);
                }
            });
        }
    }
}
