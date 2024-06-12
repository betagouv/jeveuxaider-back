<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTerritoireSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddResponsableRequest;
use App\Http\Requests\Api\TerritoireUpdateRequest;
use App\Http\Requests\TerritoireRequest;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Territoire;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TerritoireController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(Territoire::class)
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('is_published'),
                AllowedFilter::custom('search', new FiltersTerritoireSearch()),
            ])
            ->allowedIncludes([
                'banner',
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                'updated_at',
            ])
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        if ($request->has('append')) {
            $results->append(explode(',', $request->input('append')));
        }

        return $results;
    }

    public function view(Request $request, Territoire $territoire)
    {
        $territoire->load(['banner', 'logo', 'promotedOrganisations']);

        return $territoire;
    }

    public function show(Request $request, Territoire $territoire)
    {
        $territoire->load(['responsables.profile.tags', 'responsables.profile.user', 'banner', 'logo', 'promotedOrganisations']);
        $territoire->append(['missing_fields', 'completion_rate']);

        return $territoire;
    }

    public function statistics(Request $request, Territoire $territoire)
    {
        return [
            'missions_count' => Mission::ofTerritoire($territoire->id)->count(),
            'missions_available_count' => Mission::ofTerritoire($territoire->id)->available()->count(),
            'participations_count' => Participation::ofTerritoire($territoire->id)->count(),
            'participations_validated_count' => Participation::ofTerritoire($territoire->id)->where('state', 'Validée')->count(),
        ];
    }

    public function store(TerritoireRequest $request)
    {
        $territoire = Territoire::create($request->validated());

        return $territoire;
    }

    public function update(TerritoireUpdateRequest $request, Territoire $territoire)
    {
        $territoire->update($request->validated());

        return $territoire;
    }

    public function delete(Request $request, Territoire $territoire)
    {
        $territoire->responsables->map(function (User $responsable) use ($territoire) {
            $territoire->deleteResponsable($responsable);
        });

        return (string) $territoire->delete();
    }

    public function addResponsable(AddResponsableRequest $request, Territoire $territoire)
    {
        $user = User::whereEmail($request->input('email'))->first();

        if ($user->territoires()->where('id', $territoire->id)->first()) {
            abort(422, 'Cet email est déjà rattaché à ce territoire');
        }

        $territoire->addResponsable($user);

        return $territoire->responsables;
    }

    public function deleteResponsable(Request $request, Territoire $territoire, User $responsable)
    {
        $territoire->deleteResponsable($responsable);

        return $territoire->responsables;
    }

    public function availableCities(Request $request, $slugOrId)
    {
        $territoire = (is_numeric($slugOrId))
            ? Territoire::where('id', $slugOrId)->firstOrFail()
            : Territoire::where('slug', $slugOrId)->firstOrFail();

        $cities = [];
        $missionsByCity = $territoire->promotedMissions(50)->groupBy('city');

        foreach ($missionsByCity as $missions) {
            $mission = $missions->first();
            $cities[] = [
                'name' => $mission->city,
                'coordonates' => $mission->latitude . ',' . $mission->longitude,
                'zipcode' => $mission->zip,
            ];
        }

        return array_slice($cities, 0, 10);
    }

    public function exist(Request $request, $name)
    {
        $territoire = Territoire::whereIn('state', ['waiting', 'validated'])
            ->where('name', 'ILIKE', $name)
            ->first();

        if ($territoire === null) {
            return false;
        }

        return [
            'territoire_id' => $territoire->id,
            'territoire_name' => $territoire->name,
            'responsable_fullname' => $territoire->responsables->first() ? $territoire->responsables->first()->full_name : null,
        ];
    }

    public function invitations(Request $request, Territoire $territoire)
    {
        $this->authorize('viewInvitations', $territoire);

        return $territoire->invitations()->with('user.profile')->orderBy('id')->get();
    }
}
