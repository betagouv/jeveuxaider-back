<?php

namespace App\Policies;

use App\Models\Participation;
use App\Models\StructureTag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        if (Participation::role(request()->header('Context-Role'))->where('id', $participation->id)->count() > 0) {
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

        if (in_array(request()->header('Context-Role'), ['referent', 'referent_regional'])) {
            return false;
        }

        if (Participation::role(request()->header('Context-Role'))->where('id', $participation->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        if (in_array(request()->header('Context-Role'), ['admin'])) {
            return true;
        }

        return false;
    }

    public function attachStructureTag(User $user, Participation $participation, StructureTag $structureTag)
    {
        if (Participation::role(request()->header('Context-Role'))
            ->where('id', $participation->id)
            ->exists()
        ) {
            return $structureTag->structure_id === $participation->mission->structure_id || $structureTag->is_generic;
        }

        return false;
    }

    public function detachStructureTag(User $user, Participation $participation, StructureTag $structureTag)
    {
        if (Participation::role(request()->header('Context-Role'))
        ->where('id', $participation->id)
        ->exists()
    ) {
        return $structureTag->structure_id === $participation->mission->structure_id || $structureTag->is_generic;
    }

        return false;
    }
}
