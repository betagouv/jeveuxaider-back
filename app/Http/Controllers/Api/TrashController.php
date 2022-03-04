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
    public function index(Request $request, String $model)
    {
        $className = 'App\Models\\' . ucfirst($model);

        return QueryBuilder::for($className)
            ->onlyTrashed()
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTrashSearch,  $model . 's'),
            ])
            ->defaultSort('-deleted_at')
            ->paginate(config('query-builder.results_per_page'));
    }
}
