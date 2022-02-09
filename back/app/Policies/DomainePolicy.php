<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Domaine;

class DomainePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Domaine $domaine)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Domaine $domaine)
    {
        return false;
    }

    public function delete(User $user, Domaine $domaine)
    {
        return false;
    }
}
