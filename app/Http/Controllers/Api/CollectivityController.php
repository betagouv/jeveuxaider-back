<?php

namespace App\Http\Controllers\API;

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
use App\Notifications\CollectivityWaitingValidation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CollectivityController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Collectivity::class)
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::custom('search', new FiltersTitleBodyNameSearch),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $collectivity = (is_numeric($slugOrId))
            ? Collectivity::where('id', $slugOrId)->firstOrFail()
            : Collectivity::where('slug', $slugOrId)->firstOrFail();

        return $collectivity;
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

        $templatesCollection = MissionTemplate::where('published', true)->get();
        $templates = $templatesCollection->map(function ($template) use ($collectivity) {
            return [
                'id' => $template->id,
                'title' => $template->title,
                'subtitle' => $template->subtitle,
                'missions_count' => Mission::where('template_id', $template->id)
                ->where('department', $collectivity->department)
                ->count(),
                'image' => $template->image
            ];
        })->where('missions_count', '>', 0)->sortByDesc('missions_count')->values()->all();

        $cities = Mission::selectRaw('missions.city, count(missions.city) as missions_count')
            ->department($collectivity->department)
            ->available()
            ->groupBy('city')
            ->take(20)
            ->orderBy('missions_count', 'desc')
            ->get();

        foreach ($cities as $key => $city) {
            $cities[$key] = [
                'name' => $city->city,
                'missions_count' => $city->missions_count
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
            'templates' => $templates,
            'cities' => $cities
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user->isAdmin()) {
            return Collectivity::create($request->all());
        }

        // Sinon, on est dans le cas d'une inscription d'un Responsable Collectivité
        $collectivity = Collectivity::create(array_merge($request->all(), ['published' => false, 'type' => 'commune', 'state' => 'waiting']));
        $user->profile->collectivity_id = $collectivity->id;
        $user->profile->save();

        Notification::route('mail', ['achkar.joe@hotmail.fr', 'sophie.hacktiv@gmail.com', 'nassim.merzouk@beta.gouv.fr'])
            ->route('slack', 'https://hooks.slack.com/services/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')
            ->notify(new CollectivityWaitingValidation($collectivity));

        return $collectivity;
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
}
