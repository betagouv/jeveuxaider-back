<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Young;

class YoungPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Young $young)
    {
        $ids = Young::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($young->id, $ids)) {
            return true;
        }

        return false;
    }

    public function create(User $user, Young $young)
    {
        if (in_array(request()->header('Context-Role'), ['referent', 'admin'])) {
            return true;
        }

        return false;
    }

    public function update(User $user, Young $young)
    {
        $ids = Young::role(request()->header('Context-Role'))->get()->pluck('id')->all();

        if (in_array($young->id, $ids)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Young $young)
    {
        if (in_array(request()->header('Context-Role'), ['referent', 'admin'])) {
            return true;
        }

        return false;
    }
}
