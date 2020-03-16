<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use App\Models\Young;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTrashSearch;

class TrashController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('type') == 'Missions') {
            $query = Mission::class;
        } elseif ($request->input('type') == 'Volontaires') {
            $query = Young::class;
        } else {
            $query = Structure::class;
        }

        return QueryBuilder::for($query)
            ->onlyTrashed()
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTrashSearch, $request->input('type')),
            ])
            ->defaultSort('-deleted_at')
            ->paginate(config('query-builder.results_per_page'));
    }
}
