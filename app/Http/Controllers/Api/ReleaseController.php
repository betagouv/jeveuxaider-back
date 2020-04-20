<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReleaseCreateRequest;
use App\Http\Requests\Api\ReleaseUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Release;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTitleBodySearch;

class ReleaseController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->has('pagination') ? $request->input('pagination') : config('query-builder.results_per_page');

        return QueryBuilder::for(Release::class)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
            )
            ->defaultSort('-date')
            ->paginate($paginate);
    }

    public function store(ReleaseCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $release = Release::create($request->validated());

        return $release;
    }

    public function show(Release $release)
    {
        return $release;
    }

    public function update(ReleaseUpdateRequest $request, Release $release)
    {
        $release->update($request->validated());

        return $release;
    }

    public function delete(Request $request, Release $release)
    {
        return (string) $release->delete();
    }
}
