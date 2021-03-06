<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\User;
use App\Notifications\MissionAlmostFull;
use App\Notifications\ParticipationBeingProcessed;
use App\Notifications\ParticipationCanceled;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;
use Illuminate\Support\Facades\Auth;

class ParticipationObserver
{
    public function created(Participation $participation)
    {
        if ($participation->state == 'En attente de validation') {
            if ($participation->mission->responsable) {
                $participation->mission->responsable->notify(new ParticipationWaitingValidation($participation));
            }
        }

        // RESPONSE RATIO
        $structure = $participation->mission->structure;
        $structure->setResponseRatio();
        $structure->saveQuietly();

        // Maj Sendinblue
        if (config('services.sendinblue.sync')) {
            SendinblueSyncUser::dispatch($participation->profile->user);
        }

        // Update Places left & Algolia
        $participation->mission->update();

        if (
            $participation->mission->participations_max > 10 &&
            $participation->mission->places_left === 1
        ) {
            $participation->mission->responsable->notify(new MissionAlmostFull($participation->mission));
        }
    }

    public function updated(Participation $participation)
    {
        $oldState = $participation->getOriginal('state');
        $newState = $participation->state;
        $currentUser = User::find(Auth::guard('api')->user()->id);

        if ($oldState != $newState) {
            switch ($newState) {
                case 'En attente de validation':
                    if ($participation->mission->responsable && ! $currentUser->isAdmin()) {
                        $participation->mission->responsable->notify(new ParticipationWaitingValidation($participation));
                    }
                    break;
                case 'En cours de traitement':
                    if ($participation->profile && ! $currentUser->isAdmin()) {
                        $participation->profile->notify(new ParticipationBeingProcessed($participation));
                    }
                    break;
                case 'Valid??e':
                    if ($participation->profile) {
                        if (! $currentUser->isAdmin()) {
                            $participation->profile->notify(new ParticipationValidated($participation));
                        }

                        // MAJ SENDINBLUE
                        if (config('services.sendinblue.sync')) {
                            SendinblueSyncUser::dispatch($participation->profile->user);
                        }
                    }
                    break;
                case 'Annul??e':
                    if ($participation->profile && ! $currentUser->isAdmin()) {
                        $participation->profile->notify(new ParticipationCanceled($participation));
                    }
                    break;
            }
        }

        // SET STRUCTURE RESPONSE RATIO
        if ($oldState != $newState) {
            if ($oldState == 'En attente de validation') {
                $participation->mission->structure->setResponseRatio()->saveQuietly();
            }
            if ($participation->conversation) {
                if ($newState != 'Refus??e') {
                    $participation->conversation->messages()->create([
                        'content' => 'La participation a ??t?? '.mb_strtolower($newState),
                        'type' => 'contextual',
                        'contextual_state' => $newState,
                    ]);
                }
                $participation->conversation->setResponseTime()->save();
            }
        }
    }

    public function deleted(Participation $participation)
    {
        if ($participation->mission) {
            $participation->mission->update();
        }

        // MAJ SENDINBLUE
        if (config('services.sendinblue.sync')) {
            if ($participation->profile) {
                SendinblueSyncUser::dispatch($participation->profile->user);
            }
        }
    }
}
