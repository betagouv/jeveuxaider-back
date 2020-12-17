<?php

namespace App\Observers;

use App\Models\Conversation;
use App\Models\Structure;

class ConversationObserver
{
    public function updated(Conversation $conversation)
    {
        // CALCULATE AVERAGE STRUCTURE RESPONSE TIME
        if (!$conversation->getOriginal('response_time')) {
            $structure = Structure::find($conversation->conversable->mission->structure->id);
            $participationsIds = $structure->participations->pluck('id')->all();
            $structure->response_time = intval(Conversation::whereIn('conversable_id', $participationsIds)->avg('response_time'));
            $structure->save();
        }
    }
}
