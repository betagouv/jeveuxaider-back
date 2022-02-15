<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersDomaineSearch;
use App\Http\Controllers\Controller;
use App\Models\Domaine;
use App\Http\Requests\Api\DomaineCreateRequest;
use App\Http\Requests\Api\DomaineUpdateRequest;
use App\Http\Requests\Api\DomaineDeleteRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use App\Models\MissionTemplate;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use Spatie\QueryBuilder\AllowedFilter;

class DomaineController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(Domaine::class)
            ->with(['media'])
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersDomaineSearch),
            ])
            ->allowedIncludes(['media'])
            ->allowedAppends(['banner'])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $domaine = (is_numeric($slugOrId))
            ? Domaine::where('id', $slugOrId)->firstOrFail()
            : Domaine::where('slug', $slugOrId)->with(['domaine'])->firstOrFail();

        return $domaine->append(['banner', 'illustrations', 'logos_partenaires']);
    }

    public function statistics($slugOrId)
    {
        $domaine = (is_numeric($slugOrId))
            ? Domaine::where('id', $slugOrId)->firstOrFail()
            : Domaine::where('slug', $slugOrId)->firstOrFail();

        return [
            'structures_count' => Structure::domaine($domaine->id)->count(),
            'participations_count' => Participation::domaine($domaine->id)->count(),
            'volontaires_count' => Profile::domaine($domaine->id)->count(),
        ];
    }

    public function store(DomaineCreateRequest $request)
    {
        $domaine = Domaine::create($request->validated());

        return $domaine;
    }

    public function update(DomaineUpdateRequest $request, Domaine $domaine)
    {
        $domaine->update($request->validated());

        return $domaine;
    }

    // public function delete(DomaineDeleteRequest $request, Domaine $domaine)
    // {
    //     return (string) $domaine->delete();
    // }
}
