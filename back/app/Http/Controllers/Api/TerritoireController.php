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

class TerritoireController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Territoire::with(['responsables']))
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('is_published'),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $territoire = (is_numeric($slugOrId))
            ? Territoire::where('id', $slugOrId)->with('responsables')->firstOrFail()
            : Territoire::where('slug', $slugOrId)->firstOrFail();

        return $territoire->setAppends(['permissions', 'full_url']);
    }

    public function store(TerritoireRequest $request)
    {
        return Territoire::create($request->all());
    }

    public function update(TerritoireUpdateRequest $request, Territoire $territoire)
    {
        $territoire->update($request->validated());
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

    public function availableMissions(Request $request, Territoire $territoire)
    {
        $missions = Mission::territoire($territoire->id)
            ->where('state', 'Validée')
            ->where('places_left', '>', 0)
            ->where('type', 'Mission en présentiel')
            ->where(function ($query) {
                $query
                    ->where('end_date', '<', Carbon::now())
                    ->orWhereNull('end_date');
            })
            ->with('structure')
            ->inRandomOrder()
            ->limit(4)
            ->get();

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

    public function cities(Request $request, Territoire $territoire)
    {
        $cities = [];
        $cities = Mission::selectRaw('missions.city, count(missions.city) as missions_count, MIN(missions.latitude) as latitude, MIN(missions.longitude) as longitude, MIN(missions.zip) as zipcode')
            ->where('type', 'Mission en présentiel')
            ->where('state', 'Validée')
            ->where('places_left', '>', 0)
            ->whereIn('zip', $territoire->zips)
            ->where(function ($query) {
                $query
                    ->where('end_date', '<', Carbon::now())
                    ->orWhereNull('end_date');
            })
            ->groupBy('city')
            ->take(20)
            ->orderBy('missions_count', 'desc')
            ->get();

        foreach ($cities as $key => $city) {
            $cities[$key] = [
                'name' => $city->city,
                'missions_count' => $city->missions_count,
                'coordonates' => $city->latitude . ',' . $city->longitude,
                'zipcode' => $city->zipcode,
            ];
        }

        return $cities;
    }
}
