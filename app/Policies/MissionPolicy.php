<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Mission;

class MissionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Mission $mission)
    {
        return true;
    }

    public function update(User $user, Mission $mission)
    {
        if (request()->header('Context-Role') == 'analyste') {
            return false;
        }
        
        $ids = Mission::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($mission->id, $ids)) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        if (in_array(request()->header('Context-Role'), ['referent','referent_regional', 'admin'])) {
            return true;
        }

        return false;
    }
}
