<?php

namespace App\Observers;

use App\Models\Conversation;
use App\Models\Participation;
use App\Models\Structure;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;
use App\Notifications\ParticipationCanceled;
use App\Notifications\ParticipationDeclined;
use App\Notifications\ParticipationFinished;

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
        $structure = Structure::find($participation->mission->structure->id);
        $participationsCount = $structure->participations->count();
        $conversationsWithResponseTimeCount = $structure->conversations->whereNotNull('response_time')->count();
        $structure->response_ratio =  round($conversationsWithResponseTimeCount / $participationsCount * 100);
        $structure->saveQuietly();
    }

    public function updated(Participation $participation)
    {
        $oldState = $participation->getOriginal('state');
        $newState = $participation->state;

        if ($oldState != $newState) {
            switch ($newState) {
                case 'En attente de validation':
                    if ($participation->mission->responsable) {
                        $participation->mission->responsable->notify(new ParticipationWaitingValidation($participation));
                    }
                    break;
                case 'Validée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationValidated($participation));
                    }
                    break;
                case 'Annulée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationCanceled($participation));
                    }
                    break;
                case 'Effectuée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationFinished($participation));
                    }
                    break;
                case 'Refusée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationDeclined($participation));
                    }
                    break;
            }
        }

        // Response time sur la conversation si elle existe
        if ($oldState != $newState) {
            if ($oldState == 'En attente de validation') {
                $conversation = Conversation::where('conversable_id', $participation->id)
                    ->where('conversable_type', 'App\Models\Participation')
                    ->whereNull('response_time')
                    ->first();
                if ($conversation) {
                    $conversation->response_time = $participation->updated_at->timestamp - $participation->created_at->timestamp;
                    $conversation->save();
                }
            }
        }
    }

    public function deleted(Participation $participation)
    {
        $participation->mission->update();
    }
}
