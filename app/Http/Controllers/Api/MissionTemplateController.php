<?php

namespace App\Http\Controllers\API;

use App\Filters\FiltersTitleBodySearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MissionTemplateCreateRequest;
use App\Http\Requests\Api\MissionTemplateUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\MissionTemplate;
use Spatie\QueryBuilder\AllowedFilter;

class MissionTemplateController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->has('pagination') ? $request->input('pagination') : config('query-builder.results_per_page');

        return QueryBuilder::for(MissionTemplate::class)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
            )
            ->defaultSort('-updated_at')
            ->paginate($paginate);
    }

    public function store(MissionTemplateCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $missionTemplate = MissionTemplate::create($request->validated());

        return $missionTemplate;
    }

    public function show(MissionTemplate $missionTemplate)
    {
        return $missionTemplate;
    }

    public function update(MissionTemplateUpdateRequest $request, MissionTemplate $missionTemplate)
    {
        $missionTemplate->update($request->validated());

        return $missionTemplate;
    }

    public function delete(Request $request, MissionTemplate $missionTemplate)
    {
        return (string) $missionTemplate->delete();
    }
}
