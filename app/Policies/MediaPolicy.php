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
        $queries = request()->all();
        $user->loadMissing('roles');

        if (empty($queries['filter']['collection_name'])) {
            return false;
        }

        switch($queries['filter']['collection_name']) {
            case 'domaine__illustrations_mission':
            case 'domaine__illustrations_organisation':
            case 'reseau__illustrations_antennes':
                return $user->hasRole(['admin', 'referent', 'referent_regional', 'tete_de_reseau', 'responsable']);

            default:
                return false;

        }
    }

    public function create(User $user)
    {
        $params = request()->route()->parameters();
        $contextRole = request()->header('Context-Role');
        $user->loadMissing('roles');

        switch($params['modelType']) {
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
