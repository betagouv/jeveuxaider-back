<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Participation;

class ParticipationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Participation $participation)
    {
        $ids = Participation::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($participation->id, $ids)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create participation.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Participation  $profile
     * @return mixed
     */
    public function update(User $user, Participation $participation)
    {
        if ($participation->profile_id == $user->profile->id) {
            return true;
        }
        
        $ids = Participation::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($participation->id, $ids)) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        if (in_array(request()->header('Context-Role'), ['referent', 'admin'])) {
            return true;
        }

        return false;
    }
}
