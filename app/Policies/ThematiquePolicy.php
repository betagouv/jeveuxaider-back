<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Thematique;

class ThematiquePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Thematique $thematique)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Thematique $thematique)
    {
        return false;
    }

    public function delete(User $user, Thematique $thematique)
    {
        return false;
    }
}
