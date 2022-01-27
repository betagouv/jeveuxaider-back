<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Structure;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersStructureSearch;
use App\Filters\FiltersStructureWithRna;
use App\Filters\FiltersStructureWithApiId;

class RnaController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Association'))
            ->withCount('missions')
            ->allowedFilters([
                'state',
                AllowedFilter::exact('department'),
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::custom('rna', new FiltersStructureWithRna),
                AllowedFilter::custom('api_id', new FiltersStructureWithApiId),
            ])
            ->defaultSort('created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function assign(Request $request, Structure $structure)
    {
        $structure->update([
            'rna' => $request->input('rna'),
            'api_id' => $request->input('api_id')
        ]);
        return $structure;
    }
}
