<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Conversation;

class ConversationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Conversation $conversation)
    {
        $ids = $conversation->users->pluck('id')->all();

        if (in_array($user->id, $ids)) {
            return true;
        }
        
        return false;
    }

    public function update(User $user, Conversation $conversation)
    {
        return false;
    }

    public function addMessage(User $user, Conversation $conversation)
    {
        $ids = $conversation->users->pluck('id')->all();

        if (in_array($user->id, $ids)) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        return false;
    }
}
