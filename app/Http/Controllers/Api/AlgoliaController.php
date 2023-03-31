<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
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

}