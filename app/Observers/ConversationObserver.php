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
                $avgResponseTime = $structure->conversations->avg('response_time');
                if ($avgResponseTime) {
                    $structure->response_time = intval($avgResponseTime);
                }

                $structure->saveQuietly();
            }
        }
    }
}
