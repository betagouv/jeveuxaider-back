<?php

namespace App\Policies;

use App\Models\MessageTemplate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessageTemplatePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

    }

    public function view(User $user, MessageTemplate $messageTemplate)
    {
        if(MessageTemplate::ofUser($user->id)->where('id', $messageTemplate->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {

        if ($user->roles->count() > 0) {
            return true;
        }

        return false;
    }

    public function update(User $user, MessageTemplate $messageTemplate)
    {
        if(MessageTemplate::ofUser($user->id)->where('id', $messageTemplate->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function duplicate(User $user, MessageTemplate $messageTemplate)
    {
        if(MessageTemplate::ofUser($user->id)->where('id', $messageTemplate->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function delete(User $user, MessageTemplate $messageTemplate)
    {
        if($messageTemplate->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
