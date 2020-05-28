<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Tag;

class TagPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Tag $tag)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Tag $tag)
    {
        return false;
    }

    public function delete(User $user, Tag $tag)
    {
        return false;
    }
}
