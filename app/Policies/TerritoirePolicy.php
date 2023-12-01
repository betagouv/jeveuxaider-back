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
        $ids = $user->territoires()->pluck('id')->toArray();
        if (in_array($territoire->id, $ids)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Territoire $territoire)
    {
        return false;
    }

    public function viewStats(User $user, Territoire $territoire)
    {
        if ($territoire->state !== 'validated') {
            return false;
        }

        if ($user->territoires()->where('territoire_id', $territoire->id)->count() > 1) {
            return true;
        }

        return true;
    }
}
