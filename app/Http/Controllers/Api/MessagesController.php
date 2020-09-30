<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;

class MessagesController extends Controller
{
    public function test()
    {
        $user = User::find(1);
        //$conversation = Conversation::create();
        //$conversation->save();
        $conversation = Conversation::find(1);

        $message = $user->messages()->create([
            'content' => 'Hello World !',
            'conversation_id' => $conversation->id,
            'type' => 'chat'
        ]);

        $user2 = User::find(60);
        // TODO : unique user_id + conversation_id ( first or new ? create or fail ? )
        $conversation->users()->attach($user2);

        return $message;
    }
}
