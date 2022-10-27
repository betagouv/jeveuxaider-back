<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        // @todo: faire en sorte que les membres de la structure puissent voir les messages ?
        // ray($conversation->conversable->mission->structure->members->pluck('id')->toArray());
        $ids = $conversation->users()->pluck('users.id')->all();
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
