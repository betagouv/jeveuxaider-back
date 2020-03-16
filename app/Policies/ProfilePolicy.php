<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Profile;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Profile $profile)
    {
        $ids = Profile::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($profile->id, $ids)) {
            return true;
        }

        if ($user->id == $profile->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create profiles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function update(User $user, Profile $profile)
    {
        if ($user->id == $profile->user_id) {
            return true;
        }
    }
}
