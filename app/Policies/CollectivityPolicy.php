<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Collectivity;

class CollectivityPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Collectivity $collectivity)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Collectivity $collectivity)
    {
        if ($user->profile->collectivity->id == $collectivity->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Collectivity $collectivity)
    {
        return false;
    }
}
