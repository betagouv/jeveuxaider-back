<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTrashSearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TrashController extends Controller
{
    public function index(Request $request, string $model)
    {
        $className = 'App\Models\\'.ucfirst($model);

        return QueryBuilder::for($className)
            ->onlyTrashed()
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTrashSearch, $model.'s'),
            ])
            ->defaultSort('-deleted_at')
            ->paginate(config('query-builder.results_per_page'));
    }
}
