<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Participation;
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
                    // Post::class => ['comments'],
                ]);
            }
        ]))
            // ->where('log_name', 'default'))
            ->allowedIncludes([
                'causer',
                'causer.profile'
            ])
            ->allowedFilters([
                'subject_type',
                'causer_type',
                AllowedFilter::exact('subject_id'),
                AllowedFilter::exact('causer_id'),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }
}
