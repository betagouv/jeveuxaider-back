<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersUserArchivedDatasSearch;
use App\Http\Controllers\Controller;
use App\Models\UserArchivedDatas;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserArchivedDatasController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(UserArchivedDatas::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersUserArchivedDatasSearch()),
            ])
            ->allowedIncludes([
                'user',
            ])
            ->defaultSort('created_at')
            ->paginate(config('query-builder.results_per_page'));

        if ($request->has('append')) {
            $results->append(explode(',', $request->input('append')));
        }

        return $results;
    }

    public function delete(Request $request, UserArchivedDatas $userArchivedDatas)
    {
        $userArchivedDatas->delete();

        return response()->json(null, 204);
    }
}
