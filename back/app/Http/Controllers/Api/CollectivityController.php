<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersCollectivitiesDepartment;
use App\Filters\FiltersCollectivitySearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collectivity;
use App\Http\Requests\Api\CollectivityUpdateRequest;
use App\Http\Requests\Api\CollectivityDeleteRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTitleBodyNameSearch;
use App\Http\Requests\Api\CollectivityUploadRequest;
use Illuminate\Support\Str;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CollectivityController extends Controller
{
    public function all(Request $request)
    {
        return QueryBuilder::for(Collectivity::where('type', 'commune'))
            ->allowedFilters([
                'state',
                AllowedFilter::exact('published'),
                AllowedFilter::custom('search', new FiltersCollectivitySearch),
                AllowedFilter::custom('department', new FiltersCollectivitiesDepartment),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function collectivities(Request $request)
    {
        return QueryBuilder::for(Collectivity::role($request->header('Context-Role'))->where('type', 'commune')->with('structure.members'))
            ->allowedFilters([
                'state',
                AllowedFilter::exact('published'),
                AllowedFilter::custom('search', new FiltersCollectivitySearch),
                AllowedFilter::custom('department', new FiltersCollectivitiesDepartment),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function departments(Request $request)
    {
        return QueryBuilder::for(Collectivity::where('type', 'department'))
            ->allowedFilters([
                'state',
                AllowedFilter::exact('published'),
                AllowedFilter::custom('search', new FiltersTitleBodyNameSearch),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $collectivity = (is_numeric($slugOrId))
            ? Collectivity::with(['profiles', 'structure'])->where('id', $slugOrId)->firstOrFail()
            : Collectivity::where('slug', $slugOrId)->firstOrFail();

        return $collectivity->setAppends(['banner', 'logo', 'image_1', 'image_2', 'image_3', 'image_4', 'image_5', 'image_6']);
    }

    public function statistics($slugOrId)
    {
        $collectivity = (is_numeric($slugOrId))
            ? Collectivity::where('id', $slugOrId)->firstOrFail()
            : Collectivity::where('slug', $slugOrId)->firstOrFail();

        if ($collectivity->type == 'department') {
            return $this->statisticsDepartment($collectivity);
        }

        if ($collectivity->type == 'commune') {
            return $this->statisticsCommune($collectivity);
        }

        return response('Wrong type', 402);
    }

    private function statisticsCommune($collectivity)
    {
        $templates = [];

        $templatesCollection = MissionTemplate::where('published', true)->get();
        $templates = $templatesCollection->map(function ($template) use ($collectivity) {
            return [
                'id' => $template->id,
                'title' => $template->title,
                'subtitle' => $template->subtitle,
                'missions_count' => Mission::where('template_id', $template->id)
                ->whereIn('zip', $collectivity->zips)
                ->count(),
                'image' => $template->image
            ];
        })->where('missions_count', '>', 0)->sortByDesc('missions_count')->values()->all();

        return [
            'structures_count' => Structure::whereIn('zip', $collectivity->zips)->validated()->count(),
            'participations_count' => Participation::whereHas('mission', function (Builder $query) use ($collectivity) {
                $query->whereIn('zip', $collectivity->zips);
            })->count(),
            'volontaires_count' => Profile::whereIn('zip', $collectivity->zips)->count(),
            'templates' => $templates,
        ];
    }

    private function statisticsDepartment($collectivity)
    {
        $templates = $cities = [];

        // $templatesCollection = MissionTemplate::where('published', true)->get();
        // $templates = $templatesCollection->map(function ($template) use ($collectivity) {
        //     return [
        //         'id' => $template->id,
        //         'title' => $template->title,
        //         'subtitle' => $template->subtitle,
        //         'missions_count' => Mission::where('template_id', $template->id)
        //         ->where('department', $collectivity->department)
        //         ->count(),
        //         'image' => $template->image
        //     ];
        // })->where('missions_count', '>', 0)->sortByDesc('missions_count')->values()->all();

        // @todo: Seulement les missions en présentiel ?
        $cities = Mission::selectRaw('missions.city, count(missions.city) as missions_count, MIN(missions.latitude) as latitude, MIN(missions.longitude) as longitude, MIN(missions.zip) as zipcode')
            ->department($collectivity->department)
            ->available()
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

        return [
            // 'national' => [
            //     'structures_count' => Structure::validated()->count(),
            //     'volontaires_count' => Profile::whereHas('user', function (Builder $query) {
            //         $query->where('context_role', 'volontaire');
            //     })->count()
            // ],
            // 'missions_count' => Mission::department($collectivity->department)->available()->count(),
            'structures_count' => Structure::department($collectivity->department)->validated()->count(),
            'participations_count' => Participation::department($collectivity->department)->count(),
            'volontaires_count' => Profile::department($collectivity->department)->count(),
            // 'templates' => $templates,
            'cities' => $cities
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user->isAdmin()) {
            return Collectivity::create($request->all());
        }
    }

    public function update(CollectivityUpdateRequest $request, Collectivity $collectivity)
    {
        $collectivity->update($request->validated());

        return $collectivity;
    }

    public function upload(CollectivityUploadRequest $request, Collectivity $collectivity, String $field)
    {

        // Delete previous file
        if ($media = $collectivity->getFirstMedia('collectivities', ['field' => $field])) {
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
            ->withCustomProperties(['field' => $field])
            ->withManipulations([
                'large' => ['manualCrop' => $stringCropSettings],
                'thumb' => ['manualCrop' => $stringCropSettings]
            ])
            ->toMediaCollection('collectivities');

        return $collectivity;
    }

    public function uploadDelete(CollectivityDeleteRequest $request, Collectivity $collectivity, String $field)
    {
        if ($media = $collectivity->getFirstMedia('collectivities', ['field' => $field])) {
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

    public function statisticsMissions(Request $request, Collectivity $collectivity)
    {
        if ($request->has('type') && $request->input('type') == 'light') {
            return [
                'total' => Mission::collectivity($collectivity->id)->count(),
                'month' => Mission::collectivity($collectivity->id)->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
                'week' => Mission::collectivity($collectivity->id)->where('created_at', '>=', Carbon::today()->subDays(7))->count()
            ];
        }

        return [
            'total' => Mission::collectivity($collectivity->id)->count(),
            'waiting' => Mission::collectivity($collectivity->id)->whereIn('state', ['En attente de validation'])->count(),
            'validated' => Mission::collectivity($collectivity->id)->whereIn('state', ['Validée'])->count(),
            'finished' => Mission::collectivity($collectivity->id)->whereIn('state', ['Terminée'])->count(),
            'canceled' => Mission::collectivity($collectivity->id)->whereIn('state', ['Annulée'])->count(),
            'signaled' => Mission::collectivity($collectivity->id)->whereIn('state', ['Signalée'])->count(),
            'draft' => Mission::collectivity($collectivity->id)->whereIn('state', ['Brouillon'])->count(),
        ];
    }

    public function statisticsParticipations(Request $request, Collectivity $collectivity)
    {
        if ($request->has('type') && $request->input('type') == 'light') {
            return [
                'total' => Participation::collectivity($collectivity->id)->count(),
                'month' => Participation::collectivity($collectivity->id)->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
                'week' => Participation::collectivity($collectivity->id)->where('created_at', '>=', Carbon::today()->subDays(7))->count()
            ];
        }

        return [
            'total' => Participation::collectivity($collectivity->id)->count(),
            'waiting' => Participation::collectivity($collectivity->id)->whereIn('state', ['En attente de validation'])->count(),
            'validated' => Participation::collectivity($collectivity->id)->whereIn('state', ['Validée'])->count(),
            'refused' => Participation::collectivity($collectivity->id)->whereIn('state', ['Refusée'])->count(),
            'done' => Participation::collectivity($collectivity->id)->whereIn('state', ['Effectuée'])->count(),
            'canceled' => Participation::collectivity($collectivity->id)->whereIn('state', ['Annulée'])->count()
        ];
    }

    public function chartsCreated(Request $request, Collectivity $collectivity)
    {
        $year = intval($request->input('year'));
        $items = [];

        switch ($request->input('type')) {
            case 'missions':
                $model = new Mission();
            break;
            case 'participations':
                $model = new Participation();
            break;
        }

        for ($i = 1; $i < 13; $i++) {
            $items[] = $model->collectivity($collectivity->id)
                ->whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $i)
                ->count();
        }

        return [
            'year' => $year,
            'items' => $items,
        ];
    }
}
