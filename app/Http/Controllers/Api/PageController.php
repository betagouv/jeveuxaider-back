<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Page;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTitleBodySearch;

class PageController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Page::class)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
            )
            ->paginate(config('query-builder.results_per_page'));
    }

    public function store(PageRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $page = Page::create($request->validated());

        return $page;
    }

    public function show(Page $page)
    {
        return $page;
    }

    public function update(PageRequest $request, Page $page)
    {
        $page->update($request->validated());

        return $page;
    }

    public function delete(Request $request, Page $page)
    {
        return (string) $page->delete();
    }
}
