<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Page;

class PagePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }


    /**
     * Determine whether the user can update the page.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Page  $page
     * @return mixed
     */
    public function update(User $user, Page $page)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }
}
