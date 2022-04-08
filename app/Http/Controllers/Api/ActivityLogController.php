<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(ActivityLog::class)
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
