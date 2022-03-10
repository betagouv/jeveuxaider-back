<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScriptMigrateOrganisationMissionsRequest;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class ScriptController extends Controller
{
    public function migrateOrganisationMissions(ScriptMigrateOrganisationMissionsRequest $request)
    {

        $structureOrigin = Structure::withCount(['missions'])->find($request->input('origin')['id']);
        $structureDestination = Structure::find($request->input('destination')['id']);
        $missionsToMigrate = $request->input('missions');

        if(!$structureOrigin){
            abort(422, "L'organisation d'origine n'existe plus");
        }

        if(!$structureOrigin->missions_count){
            abort(422, "L'organisation d'origine n'a pas de mission");
        }

        if(!$structureDestination){
            abort(422, "L'organisation de destination n'existe plus");
        }

        if($structureDestination->id == $structureOrigin->id){
            abort(422, "Merci de sélectionner différentes organisations ! :)");
        }

        if($missionsToMigrate){
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
        if(!$request->has('profile')) {
            abort(422, "Un utilisateur est requis");
        }

        $user = User::find($request->input('profile')['user_id']);

        if(!$user){
            abort(422, "L'utilisateur n'existe plus");
        }

        $user->resetContextRole();

        return $user;
    }
}
