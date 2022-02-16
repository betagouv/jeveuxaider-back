<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Reseau;

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
            return $user->profile->teteDeReseau->id == $reseau->id;
        }

        return false;
    }
}