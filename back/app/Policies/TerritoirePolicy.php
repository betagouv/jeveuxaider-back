<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Territoire;

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
        return true;
    }

    public function update(User $user, Territoire $territoire)
    {

        $ids = $user->profile->territoires()->pluck('id')->toArray();
        if (in_array($territoire->id, $ids)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Territoire $territoire)
    {
        return false;
    }
}
