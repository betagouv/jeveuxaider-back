<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersMissionSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScriptMigrateOrganisationMissionsRequest;
use App\Models\Activity;
use App\Models\Mission;
use App\Models\Structure;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Artisan;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilderRequest;

class ScriptController extends Controller
{
    public function migrateOrganisationMissions(ScriptMigrateOrganisationMissionsRequest $request)
    {

        $structureOrigin = Structure::withCount(['missions'])->find($request->input('origin')['id']);
        $structureDestination = Structure::find($request->input('destination')['id']);
        $missionsToMigrate = $request->input('missions');

        if (!$structureOrigin) {
            abort(422, "L'organisation d'origine n'existe plus");
        }

        if (!$structureOrigin->missions_count) {
            abort(422, "L'organisation d'origine n'a pas de mission");
        }

        if (!$structureDestination) {
            abort(422, "L'organisation de destination n'existe plus");
        }

        if ($structureDestination->id == $structureOrigin->id) {
            abort(422, "Merci de sélectionner différentes organisations ! :)");
        }

        if ($missionsToMigrate) {
            Artisan::call('migrate-organisation-missions', [
                'id' => collect($missionsToMigrate)->pluck('id')->toArray(),
                '--origin' => $structureOrigin->id,
                '--destination' => $structureDestination->id,
                '--no-interaction' => true
            ]);
        } else {
            Artisan::call('migrate-organisation-missions', [
                '--origin' => $structureOrigin->id,
                '--destination' => $structureDestination->id,
                '--no-interaction' => true
            ]);
        }
    }

    public function resetUserContextRole(Request $request)
    {
        if (!$request->has('profile')) {
            abort(422, "Un utilisateur est requis");
        }

        $user = User::find($request->input('profile')['user_id']);

        if (!$user) {
            abort(422, "L'utilisateur n'existe plus");
        }

        $user->resetContextRole();

        return $user;
    }

    public function assignActivityToMissions(Request $request, Activity $activity)
    {
        $query = QueryBuilder::for(Mission::class)
            ->allowedFilters([
                'state',
                AllowedFilter::exact('structure.id'),
                AllowedFilter::exact('structure.name'),
                AllowedFilter::exact('structure.reseaux.id'),
                AllowedFilter::exact('structure.reseaux.name'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('hasActivity'),
                AllowedFilter::scope('hasTemplate'),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::scope('available'),
            ]);

        // MASS UPDATE
        $affectedRows = $query->update(['activity_id' => $activity->id]);

        return response()->json(['affected_rows' => $affectedRows], 200);
    }
}
