<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TagCreateRequest;
use App\Http\Requests\Api\TagDeleteRequest;
use App\Http\Requests\Api\TagUpdateRequest;
use App\Http\Requests\Api\TagUploadRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Tag;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTagName;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->has('pagination') ? $request->input('pagination') : config('query-builder.results_per_page');

        return QueryBuilder::for(Tag::class)
            ->allowedFilters([AllowedFilter::custom('name', new FiltersTagName), 'type'])
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

    public function upload(TagUploadRequest $request, Tag $tag)
    {

        // Delete previous file
        if ($media = $tag->getFirstMedia('tags')) {
            $media->delete();
        }

        $extension = $request->file('image')->guessExtension();
        $name = Str::random(15);

        $tag
            ->addMedia($request->file('image'))
            ->usingName($name)
            ->usingFileName($name . '.' . $extension)
            ->toMediaCollection('tags');

        return $tag;
    }

    public function uploadDelete(TagDeleteRequest $request, Tag $tag)
    {
        if ($media = $tag->getFirstMedia('tags')) {
            $media->delete();
        }
    }

    public function delete(TagDeleteRequest $request, Tag $tag)
    {
        return (string) $tag->delete();
    }
}
