<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Structure;

class StructurePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Structure $structure)
    {
        $ids = Structure::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($structure->id, $ids)) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Structure $structure)
    {
        $ids = Structure::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($structure->id, $ids)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Structure $structure)
    {
        if (in_array(request()->header('Context-Role'), ['referent','referent_regional','admin'])) {
            return true;
        }

        return false;
    }
}
