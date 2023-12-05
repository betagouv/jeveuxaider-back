<?php

namespace App\Policies;

use App\Models\Reseau;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReseauPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function update(User $user, Reseau $reseau)
    {
        if (request()->header('Context-Role') == 'tete_de_reseau') {
            return $user->reseaux()->get()->first()->id == $reseau->id;
        }

        return false;
    }

    public function viewInvitations(User $user, Reseau $reseau)
    {
        return $user->reseaux()->where('id', $reseau->id)->exists();
    }
}
