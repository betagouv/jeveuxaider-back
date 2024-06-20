<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersMissionSearch;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Mission;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;

class ScriptController extends Controller
{
    // public function migrateOrganisationMissions(ScriptMigrateOrganisationMissionsRequest $request)
    // {
    //     $structureOrigin = Structure::withCount(['missions'])->find($request->input('origin')['id']);
    //     $structureDestination = Structure::find($request->input('destination')['id']);
    //     $missionsToMigrate = $request->input('missions');

    //     if (! $structureOrigin) {
    //         abort(422, "L'organisation d'origine n'existe plus");
    //     }

    //     if (! $structureOrigin->missions_count) {
    //         abort(422, "L'organisation d'origine n'a pas de mission");
    //     }

    //     if (! $structureDestination) {
    //         abort(422, "L'organisation de destination n'existe plus");
    //     }

    //     if ($structureDestination->id == $structureOrigin->id) {
    //         abort(422, 'Merci de sélectionner différentes organisations ! :)');
    //     }

    //     if ($missionsToMigrate) {
    //         Artisan::call('migrate-organisation-missions', [
    //             'id' => collect($missionsToMigrate)->pluck('id')->toArray(),
    //             '--origin' => $structureOrigin->id,
    //             '--destination' => $structureDestination->id,
    //             '--no-interaction' => true,
    //         ]);
    //     } else {
    //         Artisan::call('migrate-organisation-missions', [
    //             '--origin' => $structureOrigin->id,
    //             '--destination' => $structureDestination->id,
    //             '--no-interaction' => true,
    //         ]);
    //     }
    // }

    public function transfertOrganisation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organisationFrom' => 'required',
            'organisationTo' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $organisationFromId = $request->input('organisationFrom.id');
        $organisationToId = $request->input('organisationTo.id');

        if($organisationFromId === $organisationToId) {
            abort(422, "Vous devez sélectionner des organisations différentes");
        }

        $organisationFrom = Structure::find($organisationFromId);
        $organisationTo = Structure::find($organisationToId);

        $organisationFrom->missions()->update([
            'structure_id' => $organisationToId
        ]);

        // $organisationFrom->missions()->unsearchable();

        $membersToMigrate = $organisationFrom->members()->get();

        if($membersToMigrate->isNotEmpty()) {
            DB::table('rolables')
                ->whereIn('user_id', $membersToMigrate->pluck('id'))
                ->where('rolable_type', 'App\Models\Structure')
                ->where('rolable_id', $organisationFrom->id)
                ->update([
                    'rolable_id' => $organisationTo->id
                ]);

            $membersToMigrate->each(function ($member) {
                $member->resetContextRole();
            });
        }

        // $organisationTo->missions()->searchable();

        return response()->json(['message' => 'Success'], 200);
    }

    public function resetUserContextRole(Request $request)
    {
        if (!$request->has('profile')) {
            abort(422, 'Un utilisateur est requis');
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
                AllowedFilter::custom('search', new FiltersMissionSearch()),
                AllowedFilter::scope('available'),
            ]);

        // MASS UPDATE
        $affectedRows = $query->update(['activity_id' => $activity->id]);

        return response()->json(['affected_rows' => $affectedRows], 200);
    }
}
