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

    public function delete(User $user, Mission $mission)
    {
        if (in_array(request()->header('Context-Role'), ['referent','referent_regional'])) {
            return true;
        }

        if (request()->header('Context-Role') == 'responsable') {
            $structureMembersProfileIds = $mission->structure->members->pluck('id')->toArray();
            if (in_array($user->profile->id, $structureMembersProfileIds) && $mission->state == 'Brouillon') {
                return true;
            }
        }

        return false;
    }

    public function changeState(User $user, Mission $mission, $newState)
    {
        if (request()->header('Context-Role') == 'responsable') {
            if (in_array($newState, ['Brouillon','En attente de validation','Annulée','Terminée'])) {
                return true;
            } elseif ($newState == 'Validée') {
                return $mission->structure->state == 'Validée' ? true : false;
            }
            return false;
        } elseif (in_array(request()->header('Context-Role'), ['referent', 'referent_regional'])) {
            if (in_array($newState, ['Signalée', 'Validée'])) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function destroy(User $user, Mission $mission)
    {
        return false;
    }

    public function restore(User $user, Mission $mission)
    {
        return false;
    }
}
