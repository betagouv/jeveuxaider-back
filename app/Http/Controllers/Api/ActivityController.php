<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Activity::class)
        ->defaultSort('-updated_at')
        ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    }
}
