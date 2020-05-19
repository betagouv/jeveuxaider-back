<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TagCreateRequest;
use App\Http\Requests\Api\TagUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Tags\Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->has('pagination') ? $request->input('pagination') : config('query-builder.results_per_page');

        return QueryBuilder::for(Tag::class)
            ->allowedFilters(['name', 'type'])
            ->defaultSort('order_column')
            ->paginate($paginate);
    }

    public function store(TagCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $tag = Tag::create($request->validated());

        return $tag;
    }

    public function show(Tag $tag)
    {
        return $tag;
    }

    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        return $tag;
    }

    public function delete(Request $request, Tag $tag)
    {
        return (string) $tag->delete();
    }
}
