<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use Illuminate\Http\Request;

class AlgoliaController extends Controller
{
    public function missions(Request $request)
    {
        $query = Mission::search($request->input('search'))
            ->with([
                'facetFilters' => $request->input('facetFilters') ?? '',
                'filters' => $request->input('filters') ?? '',
                'numericFilters' => $request->input('numericFilters') ?? '',
                'aroundLatLngViaIP' => $request->input('aroundLatLngViaIP') ?? false
            ]);

        $results = $query
            ->paginate($request->input('paginate') ?? 10)
            ->load('domaine', 'template', 'template.domaine', 'template.media', 'structure', 'illustrations', 'template.activity');

        return $results;
    }

    public function organisations(Request $request)
    {
        $query = Structure::search($request->input('search'))
            ->with([
                'restrictSearchableAttributes' => ['name'],
                'facetFilters' => $request->input('facetFilters') ?? '',
                'filters' => $request->input('filters') ?? '',
                'numericFilters' => $request->input('numericFilters') ?? '',
                'aroundLatLngViaIP' => $request->input('aroundLatLngViaIP') ?? false,
                'typoTolerance' => $request->input('typoTolerance') ?? true,
                'minWordSizefor1Typo' => $request->input('minWordSizefor1Typo') ?? 5,
                'minWordSizefor2Typos' => $request->input('minWordSizefor2Typos') ?? 10
            ]);

        $results = $query
            ->paginate($request->input('paginate') ?? 10)
            ;

        return $results;
    }

}
