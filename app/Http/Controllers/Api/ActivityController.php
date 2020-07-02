<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Activity::class)
        ->allowedFilters([
            'subject_type',
            AllowedFilter::exact('subject_id'),
        ])
        ->defaultSort('-updated_at')
        ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    }
}
