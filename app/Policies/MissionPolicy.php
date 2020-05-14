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
        if (in_array(request()->header('Context-Role'), ['referent','referent_regional'])) {
            return true;
        }

        return false;
    }

    public function changeState(User $user, Mission $mission, $newState)
    {
        if (request()->header('Context-Role') == 'responsable') {
            if ($newState == 'Brouillon' || $newState == 'Annulée') {
                return true;
            } elseif ($newState == 'Validée') {
                return $mission->structure->state == 'Validée' ? true : false;
            }
            return false;
        } elseif (in_array(request()->header('Context-Role'), ['referent', 'referent_regional'])) {
            return true;
        }
        return false;
    }
}
