<?php

namespace App\Policies;

use App\Models\UserAlert;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserAlertPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, UserAlert $alert)
    {
        if ($user->id === $alert->user_id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, UserAlert $alert)
    {
        if ($user->id === $alert->user_id) {
            return true;
        }

        return false;
    }
}
