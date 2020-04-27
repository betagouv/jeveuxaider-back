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
use App\Http\Requests\Api\CollectivityUploadRequest;
use Illuminate\Support\Str;

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

    public function show($slugOrId)
    {
        return is_numeric($slugOrId)
            ? Collectivity::where('id', $slugOrId)->firstOrFail()
            : Collectivity::where('slug', $slugOrId)->firstOrFail();
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
        $collectivity->update($request->validated());

        return $collectivity;
    }

    public function upload(CollectivityUploadRequest $request, Collectivity $collectivity)
    {

        // Delete previous file
        if ($media = $collectivity->getFirstMedia('collectivities')) {
            $media->delete();
        }

        $data = $request->all();
        $extension = $request->file('image')->guessExtension();
        $name = Str::random(30);

        $cropSettings = json_decode($data['cropSettings']);
        $stringCropSettings = implode(",", [
            $cropSettings->width,
            $cropSettings->height,
            $cropSettings->x,
            $cropSettings->y
        ]);

        $collectivity
            ->addMedia($request->file('image'))
            ->usingName($name)
            ->usingFileName($name . '.' . $extension)
            ->withManipulations([
                'large' => ['manualCrop' => $stringCropSettings],
                'thumb' => ['manualCrop' => $stringCropSettings]
            ])
            ->toMediaCollection('collectivities');

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
