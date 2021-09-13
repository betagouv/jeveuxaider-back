<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Temoignage;
use Illuminate\Auth\Access\Response;

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

    public function create()
    {
        $temoignagesCount = Temoignage::where('participation_id', request("participation_id"))->count();
        if ($temoignagesCount > 0) {
            Response::deny('Vous avez déjà soumis votre témoignage pour cette mission !', 403);
        }

        return Response::allow();
    }

    // public function update(User $user, Temoignage $temoignage)
    // {
    //     if ($temoignage->profile_id == $user->profile->id) {
    //         return true;
    //     }

    //     if (in_array(request()->header('Context-Role'), ['referent','referent_regional'])) {
    //         return false;
    //     }

    //     $ids = Temoignage::role(request()->header('Context-Role'))->get()->pluck('id')->all();

    //     if (in_array($temoignage->id, $ids)) {
    //         return true;
    //     }

    //     return false;
    // }

    // public function delete()
    // {
    //     if (in_array(request()->header('Context-Role'), ['admin'])) {
    //         return true;
    //     }

    //     return false;
    // }
}
