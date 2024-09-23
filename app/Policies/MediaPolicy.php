<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\MissionTemplate;
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
                return $user->hasRole($contextRole) && Structure::role($contextRole)->where('id', $params['modelId'])->exists() && $params['collectionName'] === 'structure__logo';

            case 'territoire':
                return $user->territoires()->where('id', $params['modelId'])->exists();

            case 'mission_template':
                return $user->hasRole('tete_de_reseau');

            default:
                return false;
        }
    }

    public function update(User $user, Media $media)
    {
        $contextRole = request()->header('Context-Role');
        $user->loadMissing('roles');

        switch($media->model_type) {
            case 'App\Models\Profile':
                return $user->profile->id === $media->model_id;

            case 'App\Models\Structure':
                return $user->hasRole($contextRole) && Structure::role($contextRole)->where('id', $media->model_id)->exists() && $media->collection_name === 'structure__logo';

            case 'App\Models\Territoire':
                return $user->territoires()->where('id', $media->model_id)->exists();

            case 'App\Models\MissionTemplate':
                return $contextRole = 'tete_de_reseau' && $user->hasRole($contextRole) && MissionTemplate::role($contextRole)->where('id', $media->model_id)->exists();

            default:
                return false;
        }
    }

    public function delete(User $user, Media $media)
    {
        $contextRole = request()->header('Context-Role');
        $user->loadMissing('roles');

        switch($media->model_type) {
            case 'App\Models\Profile':
                return $user->profile->id === $media->model_id;

            case 'App\Models\Structure':
                return $user->hasRole($contextRole) && Structure::role($contextRole)->where('id', $media->model_id)->exists() && $media->collection_name === 'structure__logo';

            case 'App\Models\Territoire':
                return $user->territoires()->where('id', $media->model_id)->exists();

            case 'App\Models\MissionTemplate':
                return $contextRole = 'tete_de_reseau' && $user->hasRole($contextRole) && MissionTemplate::role($contextRole)->where('id', $media->model_id)->exists();

            default:
                return false;
        }
    }
}
