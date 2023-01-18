<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersActivityLogsSearch;
use App\Filters\FiltersActivityLogsType;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Participation;
use App\Models\Profile;
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
            // ->where('log_name', 'default'))
            ->allowedIncludes([
                'causer',
                'causer.profile',
            ])
            ->allowedFilters([
                'subject_type',
                'causer_type',
                'log_name',
                AllowedFilter::exact('subject_id'),
                AllowedFilter::exact('causer_id'),
                AllowedFilter::custom('search', new FiltersActivityLogsSearch),
                AllowedFilter::custom('type', new FiltersActivityLogsType),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }
}
