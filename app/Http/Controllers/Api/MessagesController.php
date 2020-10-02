<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        //$conversation->users()->attach($user2);

        return $message;
    }
    
    public function store(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        $message = $currentUser->sendMessage(request('conversation_id'), request('content'));

        return $message;
    }
}
