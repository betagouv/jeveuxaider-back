<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Http\Requests\Api\MissionUpdateRequest;
use App\Http\Requests\Api\MissionStructureRequest;
use App\Http\Requests\Api\MissionDuplicateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MissionsExport;
use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMissionDate;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionIsTemplate;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionPriorityAvailable;
use App\Filters\FiltersMissionPublicsVolontaires;
use App\Filters\FiltersProfileSkill;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileZips;
use App\Http\Requests\Api\MissionDeleteRequest;
use App\Models\NotificationTemoignage;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Tag;
use App\Services\ApiEngagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Notifications\NotificationTemoignageCreate;
use Spatie\QueryBuilder\AllowedInclude;

class MissionController extends Controller
{
    public function prioritaires(Request $request)
    {
        return  QueryBuilder::for(Mission::where('is_priority', true))
            ->with(['domaine', 'template', 'template.domaine', 'template.photo', 'illustrations', 'structure'])
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::custom('available', new FiltersMissionPriorityAvailable),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function index(Request $request)
    {
        $result = QueryBuilder::for(Mission::role($request->header('Context-Role')))
            ->with(['domaine', 'template', 'template.domaine', 'structure'])
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('id'),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('responsable.id'),
                AllowedFilter::exact('template.id'),
                AllowedFilter::exact('structure.id'),
                AllowedFilter::exact('structure.name'),
                'structure.statut_juridique',
                AllowedFilter::exact('structure.reseaux.id'),
                AllowedFilter::exact('structure.reseaux.name'),
                AllowedFilter::exact('is_snu_mig_compatible'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::scope('ofActivity'),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('date', new FiltersMissionDate),
                AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::scope('available'),
                AllowedFilter::custom('is_template', new FiltersMissionIsTemplate),
            ])
            ->allowedIncludes([
                'template.photo',
                'illustrations',
                AllowedInclude::count('participationsCount')
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                'updated_at',
                'places_left'
            ])
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

            $result->append('has_places_left');
            return $result;
    }

    public function show(Request $request, $id)
    {

        if (is_numeric($id)) {
            $mission = Mission::with(['structure.members:id,first_name,last_name,mobile,email', 'template.domaine', 'domaine', 'domaineSecondary', 'responsable', 'skills', 'template.photo', 'illustrations', 'structure.illustrations', 'structure.overrideImage1', 'structure.logo'])->withCount('temoignages')->where('id', $id)->first();
            if ($mission) {
                $mission->append(['full_address', 'has_places_left']);
            }
        } else {
            // API ENGAGEMENT
            $api = new ApiEngagement();
            $mission = $api->getMission($id);
            if ($mission) {
                $mission['isFromApi'] = true;
            }
        }

        if (!$mission) {
            abort(404, 'Cette mission n\'existe pas');
        }

        return $mission['isFromApi'] ? $mission : $mission->append('participations_total', 'full_url');
    }

    public function update(MissionUpdateRequest $request, Mission $mission)
    {
        if ($request->has('skills')) {
            $skills =  collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'mission_skills'];
            });
            $mission->skills()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations =  collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'mission_illustrations'];
            });
            $mission->illustrations()->sync($values);
        }

        $mission->update($request->validated());
        return $mission;
    }

    public function delete(MissionDeleteRequest $request, Mission $mission)
    {
        $relatedParticipationsCount = Participation::where('mission_id', $mission->id)->count();

        if ($relatedParticipationsCount) {
            abort('422', "Cette mission est reliée à {$relatedParticipationsCount} participation(s)");
        }

        return (string) $mission->delete();
    }

    public function duplicate(MissionDuplicateRequest $request, Mission $mission)
    {
        if ($mission->template_id && (!$mission->template->published || $mission->template->state !== 'validated')) {
            abort('422', "Le modèle de cette mission n'est plus disponible.");
        }

        $new = $mission->duplicate();

        activity()
            ->causedBy($request->user())
            ->performedOn($new)
            ->withProperties(['attributes' => ['from_id' => $mission->id]])
            ->event('duplicated')
            ->log('duplicated');

        return $new;
    }

    public function benevoles(Request $request, Mission $mission)
    {
        if ($request->header('Context-Role') !== 'admin' && (!$mission->has_places_left || $mission->state != 'Validée' || $mission->structure->state != 'Validée')) {
            abort(401, "Vous n'êtes pas autorisé à accéder à ce contenu");
        }

        $domaineId = $mission->template_id ? $mission->template->domaine_id : $mission->domaine_id;

        $profilesQueryBuilder = Profile::where('is_visible', true)
            ->whereHas('user', function (Builder $query) {
                $query->whereNull('anonymous_at');
            })
            ->ofDomaine($domaineId)
            ->whereDoesntHave('participations', function (Builder $query) use ($mission) {
                $query->where('mission_id', $mission->id);
            });

        if ($mission->type == 'Mission en présentiel') {
            $profilesQueryBuilder->department($mission->department);
        }


        return QueryBuilder::for($profilesQueryBuilder)
            ->allowedIncludes([
                'user',
                'participationsValidatedCount',
                'avatar',
            ])
            ->allowedFilters(
                'zip',
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                AllowedFilter::scope('minimum_commitment')
                // AllowedFilter::custom('zips', new FiltersProfileZips),
                // AllowedFilter::custom('domaine', new FiltersProfileTag),
                // AllowedFilter::custom('skills', new FiltersProfileSkill),
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function similar(Request $request, Mission $mission)
    {
        // X-sell sur le domain d'action ET la ville
        $query = Mission::search('')
            ->where('id', '!=', $mission->id)
            ->with([
                'facetFilters' => 'domaine_name:' . $mission->domaine_name,
                // Sans prendre en compte l'API, sinon erreur ScoutExtended ObjectID seems invalid
                'filters' => 'provider:reserve_civique',
            ]);
        if ($mission->latitude && $mission->longitude) {
            $query->aroundLatLng($mission->latitude, $mission->longitude);
        }
        return $query->paginate(10)->load('domaine', 'template', 'template.domaine', 'template.media', 'structure');
    }
}
