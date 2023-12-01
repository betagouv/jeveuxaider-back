<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $currentUser, User $user)
    {
        if ($currentUser->id == $user->id) {
            return true;
        }

        return false;
    }

    public function exportReseaux(User $user)
    {
        if (request()->header('Context-Role') === 'admin' && $user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function exportStructures(User $user)
    {
        if (request()->header('Context-Role') === 'admin' && $user->isAdmin()) {
            return true;
        }

        if (request()->header('Context-Role') === 'referent' && $user->hasRole('referent')) {
            return true;
        }

        if (request()->header('Context-Role') === 'tete_de_reseau' && $user->hasRole('tete_de_reseau')) {
            return true;
        }

        return false;
    }

    public function exportMissions(User $user)
    {
        if (request()->header('Context-Role') === 'admin' && $user->isAdmin()) {
            return true;
        }

        if (request()->header('Context-Role') === 'referent' && $user->hasRole('referent')) {
            return true;
        }

        if (request()->header('Context-Role') === 'tete_de_reseau' && $user->hasRole('tete_de_reseau')) {
            return true;
        }

        if (request()->header('Context-Role') === 'responsable' && $user->hasRole('responsable')) {
            return true;
        }

        return false;
    }

    public function exportParticipations(User $user)
    {

        if (request()->header('Context-Role') === 'tete_de_reseau' && $user->hasRole('tete_de_reseau')) {
            return true;
        }

        if (request()->header('Context-Role') === 'responsable' && $user->hasRole('responsable')) {
            return true;
        }

        return false;
    }

    public function exportTerritoires(User $user)
    {
        if (request()->header('Context-Role') === 'admin' && $user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function exportProfiles(User $user)
    {
        if (request()->header('Context-Role') === 'referent' && $user->hasRole('referent') && $user->profile->can_export_profiles) {
            return true;
        }

        return false;
    }

}
