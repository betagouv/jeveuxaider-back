<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTemplatesWithReseau;
use App\Filters\FiltersTitleBodySearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MissionTemplateCreateRequest;
use App\Http\Requests\Api\MissionTemplateUpdateRequest;
use App\Http\Requests\Api\MissionTemplateDeleteRequest;
use App\Models\Mission;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\MissionTemplate;
use App\Models\Participation;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class MissionTemplateController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->has('pagination') ? $request->input('pagination') : config('query-builder.results_per_page');

        return QueryBuilder::for(MissionTemplate::role($request->header('Context-Role'))->with(['domaine', 'reseau'])->withCount(['missions']))
            ->allowedFilters(
                'state',
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
                AllowedFilter::exact('domaine.id'),
                AllowedFilter::exact('published'),
                AllowedFilter::scope('of_reseau'),
                AllowedFilter::callback('with_reseaux', new FiltersTemplatesWithReseau)
            )
            ->allowedIncludes(['photo'])
            ->defaultSort('-updated_at')
            ->paginate($paginate);
    }

    public function store(MissionTemplateCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        return MissionTemplate::create($request->validated());
    }

    public function show(MissionTemplate $missionTemplate)
    {
        return $missionTemplate->load(['reseau','photo','domaine']);
    }

    public function statistics(MissionTemplate $missionTemplate)
    {
        return [
                'missions_count' => Mission::where('template_id', $missionTemplate->id)->count(),
                'missions_available_count' => Mission::available()->where('template_id', $missionTemplate->id)->count(),
                'participations_count' => Participation::whereHas('mission', function (Builder $query) use ($missionTemplate) {
                    $query->where('template_id', $missionTemplate->id);
                })->count(),
                'participations_validated_count' => Participation::where('state', 'Validée')->whereHas('mission', function (Builder $query) use ($missionTemplate) {
                    $query->where('template_id', $missionTemplate->id);
                })->count(),
            ];
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
