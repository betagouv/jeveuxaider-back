<?php

namespace App\Observers;

use App\Models\Conversation;
use App\Models\Structure;

class ConversationObserver
{
    public function updated(Conversation $conversation)
    {
        // CALCULATE AVERAGE STRUCTURE RESPONSES WHEN RESPONSE TIME IS FILLED
        if ($conversation->wasChanged('response_time')) {
            $conversation->load('conversable.mission.structure');
            $conversation->conversable->mission->structure->setResponseTime()->saveQuietly();
        }
    }
}
