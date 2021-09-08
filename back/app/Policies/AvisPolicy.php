<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Avis;
use Illuminate\Auth\Access\Response;

class AvisPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Avis $avis)
    {
        $ids = Avis::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($avis->id, $ids)) {
            return true;
        }

        return false;
    }

    public function create()
    {
        ray("AVIS POLICY", request());

        $avisCount = Avis::where('participation_id', request("participation_id"))->count();

        ray("participation ID : " . request("participation_id"));

        if ($avisCount > 0) {
            Response::deny('Vous avez déjà soumis votre avis pour cette mission !', 403);
        }

        return Response::allow();
    }

    // public function update(User $user, Avis $avis)
    // {
    //     if ($avis->profile_id == $user->profile->id) {
    //         return true;
    //     }

    //     if (in_array(request()->header('Context-Role'), ['referent','referent_regional'])) {
    //         return false;
    //     }

    //     $ids = Avis::role(request()->header('Context-Role'))->get()->pluck('id')->all();

    //     if (in_array($avis->id, $ids)) {
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
