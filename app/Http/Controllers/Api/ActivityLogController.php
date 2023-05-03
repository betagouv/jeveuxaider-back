<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersActivityLogsSearch;
use App\Filters\FiltersActivityLogsType;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Includes\IncludeInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(ActivityLog::with([
                'subject' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Participation::class => ['mission'],
                        Structure::class => [],
                        Mission::class => [],
                        Profile::class => [],
                    ]);
                }
            ]))
            ->allowedIncludes([
                'causer',
                'causer.profile',
            ])
            ->allowedFilters([
                'subject_type',
                'causer_type',
                'log_name',
                'description',
                AllowedFilter::exact('subject_id'),
                AllowedFilter::exact('causer_id'),
                AllowedFilter::custom('search', new FiltersActivityLogsSearch),
                AllowedFilter::custom('type', new FiltersActivityLogsType),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, ActivityLog $activityLog)
    {
        $activityLog->load(['causer.roles', 'causer.profile.tags', 'subject']);
        return $activityLog;
    }

    public function structureStatesChanges(Request $request, Structure $structure)
    {
        return ActivityLog::with('causer.profile')
            ->where('subject_id', $structure->id)
            ->where('subject_type', 'App\Models\Structure')
            ->where('properties->attributes->state','<>', '')
            ->orderBy('created_at')
            ->get();
    }

    public function missionStatesChanges(Request $request, Mission $mission)
    {
        return ActivityLog::with('causer.profile')
            ->where('subject_id', $mission->id)
            ->where('subject_type', 'App\Models\Mission')
            ->where('properties->attributes->state','<>', '')
            ->orderBy('created_at')
            ->get();
    }

    public function participationStatesChanges(Request $request, Participation $participation)
    {
        return ActivityLog::with('causer.profile')
            ->where('subject_id', $participation->id)
            ->where('subject_type', 'App\Models\Participation')
            ->where('properties->attributes->state','<>', '')
            ->orderBy('created_at')
            ->get();
    }
}
