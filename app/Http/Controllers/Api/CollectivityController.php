<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Collectivity;
use App\Http\Requests\Api\CollectivityCreateRequest;
use App\Http\Requests\Api\CollectivityUpdateRequest;
use App\Http\Requests\Api\CollectivityDeleteRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTitleBodySearch;

class CollectivityController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(Collectivity::class)
            ->allowedFilters([
                'state',
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
            ])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Collectivity $collectivity)
    {
        return $collectivity;
    }

    // public function submit(CollectivitySubmitRequest $request)
    // {
    //     if (!$request->validated()) {
    //         return $request->validated();
    //     }

    //     $collectivity = Collectivity::create($request->validated());

    //     $profile = Profile::whereEmail(request('email'))->first();

    //     if (!$profile) {
    //         $profile = Profile::create($request->validated());
    //     }

    //     $collectivity->profile()->save($profile);

    //     return $collectivity;
    // }

    public function store(CollectivityCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $collectivity = Collectivity::create($request->validated());

        return $collectivity;
    }

    public function update(CollectivityUpdateRequest $request, Collectivity $collectivity)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $collectivity->update($request->validated());

        return $collectivity;
    }

    public function delete(CollectivityDeleteRequest $request, Collectivity $collectivity)
    {
        return (string) $collectivity->delete();
    }

    public function destroy($id)
    {
        $collectivity = Collectivity::withTrashed()->findOrFail($id);
        return (string) $collectivity->forceDelete();
    }
}
