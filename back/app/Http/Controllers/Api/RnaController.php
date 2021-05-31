<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Structure;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersStructureLieu;
use App\Filters\FiltersStructureSearch;
use App\Filters\FiltersStructureWithRna;

class RnaController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Structure::role($request->header('Context-Role')))
            ->withCount('missions')
            ->allowedFilters([
                'state',
                'statut_juridique',
                AllowedFilter::exact('department'),
                AllowedFilter::custom('lieu', new FiltersStructureLieu),
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::custom('rna', new FiltersStructureWithRna),
            ])
            ->defaultSort('created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function assign(Request $request, Structure $structure)
    {
        $structure->update(['rna' => $request->input('rna')]);
        return $structure;
    }
}
