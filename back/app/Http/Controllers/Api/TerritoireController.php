<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TerritoireRequest;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Territoire;

class TerritoireController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Territoire::class)
            ->allowedFilters([
                'state',
                AllowedFilter::exact('is_published'),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $territoire = (is_numeric($slugOrId))
            ? Territoire::where('id', $slugOrId)->firstOrFail()
            : Territoire::where('slug', $slugOrId)->firstOrFail();

        return $territoire->setAppends(['banner', 'logo']);
    }

    public function store(TerritoireRequest $request)
    {
        return Territoire::create($request->all());
    }

    public function update(TerritoireRequest $request, Territoire $territoire)
    {
        $territoire->update($request->validated());
        return $territoire;
    }

    public function delete(Request $request, Territoire $territoire)
    {
        return (string) $territoire->delete();
    }

    public function destroy($id)
    {
        $territoire = Territoire::withTrashed()->findOrFail($id);
        return (string) $territoire->forceDelete();
    }
}
