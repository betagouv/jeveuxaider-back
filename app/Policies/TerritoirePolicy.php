<?php

namespace App\Policies;

use App\Models\Territoire;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TerritoirePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Territoire $territoire)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Territoire $territoire)
    {
        return $user->territoires()->where('id', $territoire->id)->exists();
    }

    public function delete(User $user, Territoire $territoire)
    {
        return false;
    }

    // public function viewStats(User $user, Territoire $territoire)
    // {
    //     if ($territoire->state !== 'validated') {
    //         return false;
    //     }

    //     if ($user->territoires()->where('id', $territoire->id)->count() > 1) {
    //         return true;
    //     }

    //     return true;
    // }

    public function viewInvitations(User $user, Territoire $territoire)
    {
        return $user->territoires()->where('id', $territoire->id)->exists();
    }
}
