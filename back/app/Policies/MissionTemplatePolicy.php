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
        if (in_array(request()->header('Context-Role'), ['tete_de_reseau'])) {
            return true;
        }
        return false;
    }

    public function update(User $user, MissionTemplate $missionTemplate)
    {
        if (in_array(request()->header('Context-Role'), ['tete_de_reseau'])) {
            return true;
        }
        return false;
    }

    public function delete(User $user, MissionTemplate $missionTemplate)
    {
        return false;
    }
}
