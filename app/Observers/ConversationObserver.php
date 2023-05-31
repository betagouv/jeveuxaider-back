<?php

namespace App\Observers;

use App\Models\Conversation;
use App\Models\Structure;

class ConversationObserver
{
    public function updated(Conversation $conversation)
    {
        // Calculate average structure response time when response_time is filled
        if ($conversation->wasChanged('response_time')) {
            $conversation->load('conversable.mission.structure');
            $conversation->conversable->mission->structure->calculateScore();
        }
    }
}
