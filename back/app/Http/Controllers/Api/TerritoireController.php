<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TerritoireUpdateRequest;
use App\Http\Requests\TerritoireRequest;
use App\Models\Mission;
use App\Models\Profile;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Territoire;

class TerritoireController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Territoire::with(['responsables']))
            ->allowedFilters([
                'state',
                AllowedFilter::exact('is_published'),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $territoire = (is_numeric($slugOrId))
            ? Territoire::where('id', $slugOrId)->with('responsables')->firstOrFail()
            : Territoire::where('slug', $slugOrId)->firstOrFail();

        return $territoire;
        // return $territoire->setAppends(['completion_rate', 'full_url']);
    }

    public function store(TerritoireRequest $request)
    {
        return Territoire::create($request->all());
    }

    public function update(TerritoireUpdateRequest $request, Territoire $territoire)
    {
        $territoire->update($request->validated());
        return $territoire;
    }

    public function delete(Request $request, Territoire $territoire)
    {
        return (string) $territoire->delete();
    }

    public function responsables(Request $request, Territoire $territoire)
    {
        return $territoire->responsables;
    }

    public function invitations(Request $request, Territoire $territoire)
    {
        return $territoire->invitations;
    }

    // public function missions(Request $request, Territoire $territoire)
    // {
    //     $query = QueryBuilder::for(Mission::with('domaine'))
    //         ->allowedAppends(['domaines'])
    //         ->available()
    //         ->territoire($territoire->id)
    //         ->with('structure');

    //     return $query
    //         ->defaultSort('-updated_at')
    //         ->allowedSorts(['places_left', 'type'])
    //         ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    // }

    public function deleteResponsable(Request $request, Territoire $territoire, Profile $profile)
    {
        $territoire->deleteResponsable($profile);
        return $territoire->responsables;
    }
}
