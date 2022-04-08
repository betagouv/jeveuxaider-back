<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivityUpdateRequest;
use App\Models\Mission;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersActivitySearch;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Participation;
use Illuminate\Database\Eloquent\Builder;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $results =  QueryBuilder::for(Activity::class)
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('is_published'),
                AllowedFilter::custom('search', new FiltersActivitySearch),
            ])
            ->allowedIncludes([
                'banner',
            ])
            ->defaultSort('-name')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        return $results;
    }

    public function show($slugOrId)
    {
        $activity = (is_numeric($slugOrId))
            ? Activity::where('id', $slugOrId)->with(['banner', 'promotedOrganisations', 'domaines'])->firstOrFail()
            : Activity::where('slug', $slugOrId)->with(['banner', 'promotedOrganisations'])->firstOrFail();

        return $activity;
    }

    public function statistics(Activity $activity)
    {
        return [
            'missions_count' => Mission::ofActivity($activity->id)->count(),
            'missions_available_count' => Mission::ofActivity($activity->id)->available()->count(),
            'participations_count' => Participation::ofActivity($activity->id)->count(),
            'participations_validated_count' => Participation::ofActivity($activity->id)->where('state', 'Validée')->count(),
        ];
    }

    public function store(ActivityRequest $request)
    {
        $activity = Activity::create($request->validated());

        if ($request->has('domaines')) {
            $domaines =  collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'activity_domaines'];
            });
            $activity->domaines()->sync($values);
        }

        return $activity;
    }

    public function update(ActivityUpdateRequest $request, Activity $activity)
    {
        $activity->update($request->validated());

        if ($request->has('domaines')) {
            $domaines =  collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'activity_domaines'];
            });
            $activity->domaines()->sync($values);
        }

        return $activity;
    }

}
