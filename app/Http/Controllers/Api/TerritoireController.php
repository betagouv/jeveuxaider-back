<?php

namespace App\Http\Controllers\Api;

use App\Exports\TerritoiresExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TerritoireUpdateRequest;
use App\Http\Requests\Api\TerritoireDeleteRequest;
use App\Http\Requests\TerritoireRequest;
use App\Models\Mission;
use App\Models\Profile;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Territoire;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Filters\FiltersTerritoireSearch;
use App\Jobs\NotifyUserOfCompletedExport;
use App\Models\Participation;
use Illuminate\Database\Eloquent\Builder;

class TerritoireController extends Controller
{
    public function index(Request $request)
    {
        $results =  QueryBuilder::for(Territoire::class)
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('is_published'),
                AllowedFilter::custom('search', new FiltersTerritoireSearch),
            ])
            ->allowedIncludes([
                'banner',
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $results->append(['places_left']);

        return $results;
    }

    public function show($slugOrId)
    {
        $territoire = (is_numeric($slugOrId))
            ? Territoire::where('id', $slugOrId)->with(['responsables', 'banner', 'logo', 'promotedOrganisations'])->firstOrFail()->append(['missing_fields','completion_rate'])
            : Territoire::where('slug', $slugOrId)->with(['banner', 'logo', 'promotedOrganisations'])->firstOrFail();

        return $territoire;
    }

    public function statistics(Territoire $territoire)
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
        $territoire = Territoire::create($request->all());

        return $territoire;
    }

    public function update(TerritoireUpdateRequest $request, Territoire $territoire)
    {
        $request = $request->validated();
        $territoire->update($request);

        return $territoire;
    }

    // public function delete(Request $request, Territoire $territoire)
    // {
    //     return (string) $territoire->delete();
    // }

    public function deleteResponsable(Request $request, Territoire $territoire, Profile $responsable)
    {
        $territoire->deleteResponsable($responsable);
        return $territoire->responsables;
    }

    public function availableCities(Request $request, Territoire $territoire)
    {
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
            'responsable_fullname' => $territoire->responsables->first() ? $territoire->responsables->first()->full_name : null
        ];
    }
}
