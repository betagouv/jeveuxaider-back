<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user)
    {
        return false;
    }

    public function create(User $user)
    {
        $params = request()->route()->parameters();
        $contextRole = request()->header('Context-Role');
        $user->loadMissing('roles');

        switch($params['modelType']) {
            case 'activity':
            case 'document':
            case 'domaine':
            case 'reseau':
                return $user->isAdmin();

            case 'profile':
                return (string) $user->profile->id === $params['modelId'];

            case 'structure':
                return $user->hasRole($contextRole) && Structure::role(request()->header('Context-Role'))->where('id', $params['modelId'])->count() > 0;

            case 'territoire':
                return $user->territoires()->where('id', $params['modelId'])->exists();

            case 'mission_template':
                return in_array($contextRole, ['tete_de_reseau']);
            default:
                return false;
        }
    }

    public function update(User $user, Media $media)
    {
        //
    }

    public function delete(User $user, Media $media)
    {
        //
    }
}
