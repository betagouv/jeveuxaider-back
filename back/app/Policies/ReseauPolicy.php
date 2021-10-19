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

        if (request()->header('Context-Role') == 'referent') {
            return true;
        }

        if (request()->header('Context-Role') == 'referent_regional') {
            return true;
        }
    }

    public function view(User $user, Reseau $reseau)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Reseau $reseau)
    {
        return false;
    }

    public function delete(User $user, Reseau $reseau)
    {
        return false;
    }

    public function destroy(User $user, Reseau $reseau)
    {
        return false;
    }

    public function restore(User $user, Reseau $reseau)
    {
        return false;
    }

    public function manage(User $user, Reseau $reseau)
    {
        return false;
    }
}
