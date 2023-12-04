<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Invitation $invitation)
    {
        return true;
    }

    public function create(User $user, $request)
    {
        if (!in_array(request()->header('Context-Role'), ['tete_de_reseau', 'responsable', 'responsable_territoire'])) {
            return false;
        }

        if ($request->input('role') == 'responsable_organisation') {
            if(request()->header('Context-Role') === 'tete_de_reseau') {
                return Structure::role(request()->header('Context-Role'))->whereId($request->input('invitable_id'))->exists();
            }
            return $user->structures()->whereId($request->input('invitable_id'))->exists();
        }

        if ($request->input('role') == 'responsable_territoire') {
            return $user->territoires()->whereId($request->input('invitable_id'))->exists();
        }

        if ($request->input('role') == 'responsable_reseau') {
            return $user->reseaux()->whereId($request->input('invitable_id'))->exists();
        }

        if ($request->input('role') == 'responsable_antenne') {
            return $user->reseaux()->whereId($request->input('invitable_id'))->exists();
        }

        return false;
    }

    public function update(User $user, Invitation $invitation)
    {
        if($user->id === $invitation->user_id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Invitation $invitation)
    {

        if($user->id === $invitation->user_id) {
            return true;
        }

        return false;
    }
}
