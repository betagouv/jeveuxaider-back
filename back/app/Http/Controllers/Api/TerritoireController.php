<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TerritoireUpdateRequest;
use App\Http\Requests\Api\TerritoireUploadRequest;
use App\Http\Requests\Api\TerritoireDeleteRequest;
use App\Http\Requests\TerritoireRequest;
use App\Models\Mission;
use App\Models\Profile;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Territoire;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Filters\FiltersTerritoireSearch;

class TerritoireController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Territoire::with(['media']))
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('is_published'),
                AllowedFilter::custom('search', new FiltersTerritoireSearch),
            ])

            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $territoire = (is_numeric($slugOrId))
        ? Territoire::where('id', $slugOrId)->with(['responsables', 'structures'])->firstOrFail()
        : Territoire::where('slug', $slugOrId)->with(['structures'])->firstOrFail();

        return $territoire;
    }

    public function store(TerritoireRequest $request)
    {
        $territoire = Territoire::create($request->all());
        if (!empty($request['structures'])) {
            $ids = array_column($request['structures'], 'id');
            $territoire->structures()->sync($ids);
        }

        return $territoire;
    }

    public function update(TerritoireUpdateRequest $request, Territoire $territoire)
    {
        $request = $request->validated();
        $territoire->update($request);

        $ids = !empty($request['structures']) ? array_column($request['structures'], 'id') : [];
        $territoire->structures()->sync($ids);
        return $territoire;
    }

    public function delete(Request $request, Territoire $territoire)
    {
        return (string) $territoire->delete();
    }

    public function responsables(Request $request, Territoire $territoire)
    {
        return $territoire->responsables;
    }

    public function invitations(Request $request, Territoire $territoire)
    {
        return $territoire->invitations;
    }

    // public function missions(Request $request, Territoire $territoire)
    // {
    //     $query = QueryBuilder::for(Mission::with('domaine'))
    //         ->allowedAppends(['domaines'])
    //         ->available()
    //         ->territoire($territoire->id)
    //         ->with('structure');

    //     return $query
    //         ->defaultSort('-updated_at')
    //         ->allowedSorts(['places_left', 'type'])
    //         ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    // }

    public function deleteResponsable(Request $request, Territoire $territoire, Profile $profile)
    {
        $territoire->deleteResponsable($profile);
        return $territoire->responsables;
    }

    public function promotedMissions(Request $request, Territoire $territoire)
    {
        $missions = $territoire->promotedMissions();
        if ($missions->isEmpty()) {
            $missions = $territoire->promotedMissions(true);
        }

        return $missions;
    }

    public function upload(TerritoireUploadRequest $request, Territoire $territoire, String $field)
    {

        // Delete previous file
        if ($media = $territoire->getFirstMedia('territoires', ['field' => $field])) {
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

        $territoire
            ->addMedia($request->file('image'))
            ->usingName($name)
            ->usingFileName($name . '.' . $extension)
            ->withCustomProperties(['field' => $field])
            ->withManipulations([
                'large' => ['manualCrop' => $stringCropSettings],
                'thumb' => ['manualCrop' => $stringCropSettings]
            ])
            ->toMediaCollection('territoires');

        return $territoire;
    }

    public function uploadDelete(TerritoireDeleteRequest $request, Territoire $territoire, String $field)
    {
        if ($media = $territoire->getFirstMedia('territoires', ['field' => $field])) {
            $media->delete();
        }

        return true;
    }

    public function citiesWithAvailableMissions(Request $request, Territoire $territoire)
    {
        $cities = [];
        $missionsByCity = $territoire->promotedMissions(true, 50)->groupBy('city');

        foreach ($missionsByCity as $missions) {
            $mission = $missions->first();
            $cities[] = [
                'name' => $mission->city,
                'coordonates' => $mission->latitude . ',' . $mission->longitude,
                'zipcode' => $mission->zip,
            ];
        }

        return array_slice($cities, 0, 10);
    }
}
