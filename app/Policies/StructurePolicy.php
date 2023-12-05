<?php

namespace App\Policies;

use App\Models\Structure;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Query\Builder;

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
        if(Structure::role(request()->header('Context-Role'))->where('id', $structure->id)->count() > 0) {
            return true;
        }

        if (request()->header('Context-Role') == 'referent') {
            return $structure->has('missions', function(Builder $query) use ($user){
                $query
                    ->whereNotNull('department')
                    ->where('department', $user->departmentsAsReferent->first()->number);
            });
        }

        if (request()->header('Context-Role') == 'referent_regional') {
            return $structure->has('missions', function(Builder $query) use ($user){
                $query
                    ->whereNotNull('department')
                    ->whereIn(
                        'department',
                        config('taxonomies.regions.departments')[$user->regionsAsReferent->first()->name]
                    );
            });
        }

        return false;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Structure $structure)
    {
        if(Structure::role(request()->header('Context-Role'))->where('id', $structure->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Structure $structure)
    {
        return false;
    }

    public function unregister(User $user, Structure $structure)
    {
        if(Structure::role(request()->header('Context-Role'))->where('id', $structure->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function destroy(User $user, Structure $structure)
    {
        return false;
    }

    public function restore(User $user, Structure $structure)
    {
        return false;
    }

    public function changeState(User $user, Structure $structure) {

        if(Structure::role(request()->header('Context-Role'))->where('id', $structure->id)->count() > 0) {
            return true;
        }

        return false;
    }

    public function viewInvitations(User $user, Structure $structure)
    {
        if(request()->header('Context-Role') === 'tete_de_reseau') {
            return Structure::role(request()->header('Context-Role'))->whereId($structure->id)->exists();
        }

        return $user->structures()->whereId($structure->id)->exists();
    }
}
