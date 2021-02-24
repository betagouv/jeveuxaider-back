<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\MissionTemplate;

class MissionTemplatePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, MissionTemplate $missionTemplate)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, MissionTemplate $missionTemplate)
    {
        return false;
    }

    public function delete(User $user, MissionTemplate $missionTemplate)
    {
        return false;
    }
}
