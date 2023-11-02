<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersDomaineSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DomaineCreateRequest;
use App\Http\Requests\Api\DomaineUpdateRequest;
use App\Models\Domaine;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DomaineController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(Domaine::class)
            ->allowedFilters([
                AllowedFilter::exact('published'),
                AllowedFilter::custom('search', new FiltersDomaineSearch()),
            ])
            ->allowedIncludes([
                'banner',
                'missionTemplates',
            ])
            ->defaultSort('name')
            ->paginate(config('query-builder.results_per_page'));

        if ($request->has('append')) {
            $results->append(explode(',', $request->input('append')));
        }

        return $results;
    }

    public function show($slugOrId)
    {
        $domaine = (is_numeric($slugOrId))
            ? Domaine::where('id', $slugOrId)->firstOrFail()
            : Domaine::where('slug', $slugOrId)->firstOrFail();

        return $domaine->load(['banner', 'illustrations', 'illustrationsOrganisation', 'illustrationsMission', 'logosPartenaires', 'logosPartenairesActifs']);
    }

    public function statistics($slugOrId)
    {
        $domaine = (is_numeric($slugOrId))
            ? Domaine::where('id', $slugOrId)->firstOrFail()
            : Domaine::where('slug', $slugOrId)->firstOrFail();

        return [
            'structures_count' => Structure::ofDomaine($domaine->id)->count(),
            'participations_count' => Participation::ofDomaine($domaine->id)->count(),
            'volontaires_count' => Profile::ofDomaine($domaine->id)->count(),
            'missions_count' => Mission::ofDomaine($domaine->id)->count(),
            'missions_available_count' => Mission::ofDomaine($domaine->id)->available()->count(),
            'participations_count' => Participation::ofDomaine($domaine->id)->count(),
            'participations_validated_count' => Participation::ofDomaine($domaine->id)->where('state', 'Validée')->count(),
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

    public function delete(Request $request, Domaine $domaine)
    {
        $relatedMissionsCount = Mission::ofDomaine($domaine->id)->count();
        $relatedStructuresCount = Structure::ofDomaine($domaine->id)->count();
        $relatedReseauxCount = Reseau::ofDomaine($domaine->id)->count();

        if ($relatedMissionsCount) {
            abort('422', "Cette activité est reliée à {$relatedMissionsCount} mission(s)");
        }
        if ($relatedStructuresCount) {
            abort('422', "Cette activité est reliée à {$relatedStructuresCount} organisation(s)");
        }
        if ($relatedReseauxCount) {
            abort('422', "Cette activité est reliée à {$relatedReseauxCount} reseau(x)");
        }

        return (string) $domaine->delete();
    }
}
