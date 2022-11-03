<?php

namespace App\Policies;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        if (Mission::role(request()->header('Context-Role'))->where('id', $mission->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Mission $mission)
    {
        if (in_array(request()->header('Context-Role'), ['referent', 'referent_regional'])) {
            return true;
        }

        if (request()->header('Context-Role') == 'responsable') {
            $structureMembersIds = $mission->structure->members->pluck('user_id')->toArray();
            if (in_array($user->id, $structureMembersIds)) {
                return true;
            }
        }

        return false;
    }

    public function changeState(User $user, Mission $mission, $newState)
    {
        if (request()->header('Context-Role') == 'responsable') {
            if (in_array($newState, ['Brouillon', 'En attente de validation', 'Annulée', 'Terminée'])) {
                return true;
            } elseif ($newState == 'Validée') {
                return $mission->structure->state == 'Validée' ? true : false;
            }

            return false;
        } elseif (in_array(request()->header('Context-Role'), ['referent', 'referent_regional'])) {
            if (in_array($newState, ['Signalée', 'Validée', 'En cours de traitement'])) {
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

    public function duplicate(User $user, Mission $mission)
    {
        if (Mission::role('responsable')->where('id', $mission->id)->count() > 0 && request()->header('Context-Role') === 'responsable') {
            return true;
        }

        return false;
    }
}
