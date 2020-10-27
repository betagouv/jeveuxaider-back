<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTrashSearch;
use App\Models\Participation;

class TrashController extends Controller
{
    public function structures(Request $request)
    {
        return QueryBuilder::for(Structure::class)
            ->onlyTrashed()
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTrashSearch, 'structures'),
            ])
            ->defaultSort('-deleted_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function missions(Request $request)
    {
        return QueryBuilder::for(Mission::class)
            ->onlyTrashed()
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTrashSearch, 'missions'),
            ])
            ->defaultSort('-deleted_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function participations(Request $request)
    {
        return QueryBuilder::for(Participation::with(['profile','mission']))
            ->onlyTrashed()
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTrashSearch, 'participations'),
            ])
            ->defaultSort('-deleted_at')
            ->paginate(config('query-builder.results_per_page'));
    }
}
