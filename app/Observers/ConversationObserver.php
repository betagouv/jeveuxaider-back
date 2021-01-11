<?php

namespace App\Observers;

use App\Models\Conversation;
use App\Models\Structure;

class ConversationObserver
{
    public function created(Conversation $conversation)
    {
    }

    public function updated(Conversation $conversation)
    {
        // CALCULATE AVERAGE STRUCTURE RESPONSES WHEN RESPONSE TIME IS FILLED
        if (!$conversation->getOriginal('response_time') && $conversation->response_time) {
            $structure = Structure::find($conversation->conversable->mission->structure->id);
            $participationsCount = $structure->participations->count();
            if ($participationsCount) {
                // RESPONSE TIME
                $structure->response_time = intval($structure->conversations->avg('response_time'));

                // RESPONSE RATIO
                $conversationsWithResponseTimeCount = $structure->conversations->whereNotNull('response_time')->count();
                $structure->response_ratio =  round($conversationsWithResponseTimeCount / $participationsCount * 100);

                $structure->save();
            }
        }
    }
}
