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
            $structure->setResponseTime();
            $structure->saveQuietly();
        }
    }
}
