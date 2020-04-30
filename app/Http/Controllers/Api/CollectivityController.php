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
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;

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
        $collectivity = (is_numeric($slugOrId))
            ? Collectivity::where('id', $slugOrId)->firstOrFail()
            : Collectivity::where('slug', $slugOrId)->firstOrFail();

        $domains = config('taxonomies.mission_domaines.terms');
        $dataDomains = Mission::selectRaw('missions.name, count(missions.city) as missions_count')
            ->department($collectivity->department)
            ->available()
            ->groupBy('name')
            ->take(6)
            ->orderBy('missions_count', 'desc')
            ->get();
        foreach ($dataDomains as $key => $domain) {
            $dataDomains[$key] = [
                'key' => $domain->name,
                'name' => $domains[$domain->name],
                'missions_count' => $domain->missions_count
            ];
        }

        $dataCities = Mission::selectRaw('missions.city, count(missions.city) as missions_count')
            ->department($collectivity->department)
            ->available()
            ->groupBy('city')
            ->take(20)
            ->orderBy('missions_count', 'desc')
            ->get();
        foreach ($dataCities as $key => $city) {
            $dataCities[$key] = [
                'name' => $city->city,
                'missions_count' => $city->missions_count
            ];
        }

        $collectivity->stats = [
            'missions_count' => Mission::department($collectivity->department)->available()->count(),
            'structures_count' => Structure::department($collectivity->department)->validated()->count(),
            'participations_count' => Participation::department($collectivity->department)->count(),
            'volontaires_count' => Profile::department($collectivity->department)
                ->whereHas('user', function (Builder $query) {
                    $query->where('context_role', 'volontaire');
                })
                ->count(),
            'domains' => $dataDomains,
            'cities' => $dataCities,
        ];

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
        if (!empty($cropSettings)) {
            $stringCropSettings = implode(",", [
                $cropSettings->width,
                $cropSettings->height,
                $cropSettings->x,
                $cropSettings->y
            ]);
        } else {
            $pathName = $request->file('image')->getPathname();
            $infos = getimagesize($pathName);
            $stringCropSettings = implode(",", [
                $infos[0],
                $infos[1],
                0,
                0
            ]);
        }

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

    public function uploadDelete(CollectivityDeleteRequest $request, Collectivity $collectivity)
    {
        if ($media = $collectivity->getFirstMedia('collectivities')) {
            $media->delete();
        }
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
