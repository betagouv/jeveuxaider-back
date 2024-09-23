<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersActivityLogsSearch;
use App\Filters\FiltersActivityLogsType;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Rule;
use App\Models\Structure;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(ActivityLog::with([
            'subject' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Participation::class => [
                        'mission:id,name',
                        'profile:id,user_id,first_name,last_name',
                    ],
                    Structure::class => [],
                    Mission::class => [],
                    Profile::class => [],
                    Rule::class => [],
                ]);
            },
            'causer:id',
            'causer.profile:id,user_id,first_name,last_name',
        ]))
            ->allowedFilters([
                'subject_type',
                'causer_type',
                'log_name',
                'description',
                AllowedFilter::exact('subject_id'),
                AllowedFilter::exact('causer_id'),
                AllowedFilter::custom('search', new FiltersActivityLogsSearch()),
                AllowedFilter::custom('type', new FiltersActivityLogsType()),
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
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        return ActivityLog::with([
            'causer:id',
            'causer.profile:id,user_id,first_name,last_name',
        ])
            ->where('subject_id', $structure->id)
            ->where('subject_type', 'App\Models\Structure')
            ->where('properties->attributes->state', '<>', '')
            ->orderBy('created_at')
            ->get();
    }

    public function structure(Request $request, Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        $queryBuilder = ActivityLog::with([
            'causer:id',
            'causer.profile:id,user_id,first_name,last_name',
        ])->where('subject_type', 'App\Models\Structure')
        ->where('subject_id', $structure->id);

        return QueryBuilder::for($queryBuilder)
            ->allowedFilters([
                'subject_type',
                'causer_type',
                'log_name',
                'description',
                AllowedFilter::custom('search', new FiltersActivityLogsSearch()),
                AllowedFilter::custom('type', new FiltersActivityLogsType()),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function missionStatesChanges(Request $request, Mission $mission)
    {
        if (Auth::guard('api')->user()->cannot('update', $mission)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        return ActivityLog::with([
            'causer:id',
            'causer.profile:id,user_id,first_name,last_name',
        ])
            ->where('subject_id', $mission->id)
            ->where('subject_type', 'App\Models\Mission')
            ->where('properties->attributes->state', '<>', '')
            ->orderBy('created_at')
            ->get();
    }

    public function mission(Request $request, Mission $mission)
    {
        if (Auth::guard('api')->user()->cannot('update', $mission)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        $queryBuilder = ActivityLog::with([
            'causer:id',
            'causer.profile:id,user_id,first_name,last_name',
        ])->where('subject_type', 'App\Models\Mission')
        ->where('subject_id', $mission->id);

        return QueryBuilder::for($queryBuilder)
            ->allowedFilters([
                'subject_type',
                'causer_type',
                'log_name',
                'description',
                AllowedFilter::custom('search', new FiltersActivityLogsSearch()),
                AllowedFilter::custom('type', new FiltersActivityLogsType()),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function participationStatesChanges(Request $request, Participation $participation)
    {
        if (Auth::guard('api')->user()->cannot('update', $participation)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        return ActivityLog::with([
            'causer:id',
            'causer.profile:id,user_id,first_name,last_name',
        ])
            ->where('subject_id', $participation->id)
            ->where('subject_type', 'App\Models\Participation')
            ->where('properties->attributes->state', '<>', '')
            ->orderBy('created_at')
            ->get();
    }

    public function participation(Request $request, Participation $participation)
    {
        if (Auth::guard('api')->user()->cannot('update', $participation)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        $queryBuilder = ActivityLog::with([
            'causer:id',
            'causer.profile:id,user_id,first_name,last_name',
        ])->where('subject_type', 'App\Models\Participation')
        ->where('subject_id', $participation->id);

        return QueryBuilder::for($queryBuilder)
            ->allowedFilters([
                'subject_type',
                'causer_type',
                'log_name',
                'description',
                AllowedFilter::custom('search', new FiltersActivityLogsSearch()),
                AllowedFilter::custom('type', new FiltersActivityLogsType()),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }
}
