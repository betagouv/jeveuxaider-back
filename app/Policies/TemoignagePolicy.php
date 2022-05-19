<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Temoignage;

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
        $ids = Temoignage::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($temoignage->id, $ids)) {
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
