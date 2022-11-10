<?php

namespace App\Policies;

use App\Models\Temoignage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemoignagePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Temoignage $temoignage)
    {
        if(Temoignage::role(request()->header('Context-Role'))->where('id', $temoignage->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function update(User $user, Temoignage $temoignage)
    {
        return false;
    }

    // public function delete(User $user, Temoignage $temoignage)
    // {
    //     return false;
    // }
}
