<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Release;

class ReleasePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }


    /**
     * Determine whether the user can update the release.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function update(User $user, Release $release)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }
}
