<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTemplatesWithReseau;
use App\Filters\FiltersTitleBodySearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MissionTemplateCreateRequest;
use App\Http\Requests\Api\MissionTemplateUpdateRequest;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Participation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MissionTemplateController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(MissionTemplate::role($request->header('Context-Role')))
            ->allowedFilters(
                'state',
                AllowedFilter::custom('search', new FiltersTitleBodySearch()),
                AllowedFilter::exact('domaine.id'),
                AllowedFilter::exact('published'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('with_reseau'),
                AllowedFilter::exact('reseau.name'),
                AllowedFilter::exact('reseau.id'),
                AllowedFilter::callback('with_reseaux', new FiltersTemplatesWithReseau())
            )
            ->allowedIncludes(['photo', 'domaine', 'reseau', 'missions', 'tags'])
            ->defaultSort('-updated_at')
            ->allowedSorts('reseau_id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        if ($request->has('append')) {
            $results->append(explode(',', $request->input('append')));
        }

        return $results;
    }

    public function store(MissionTemplateCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $missionTemplate =  MissionTemplate::create($request->validated());

        if ($request->has('tags')) {
            $tags = collect($request->input('tags'));
            $values = $tags->pluck($tags, 'id')->map(function ($item) {
                return ['field' => 'mission_template_tags'];
            });
            $missionTemplate->tags()->sync($values);
        }

        return $missionTemplate;
    }

    public function show(MissionTemplate $missionTemplate)
    {
        return $missionTemplate->load(['reseau', 'photo', 'domaine', 'domaineSecondary', 'tags']);
    }

    public function statistics(MissionTemplate $missionTemplate)
    {
        return [
            'missions_count' => Mission::where('template_id', $missionTemplate->id)->count(),
            'missions_available_count' => Mission::available()->where('template_id', $missionTemplate->id)->count(),
            'participations_count' => Participation::whereHas(
                'mission',
                function (Builder $query) use ($missionTemplate) {
                    $query->where('template_id', $missionTemplate->id);
                }
            )->count(),
            'participations_validated_count' => Participation::where('state', 'Validée')->whereHas(
                'mission',
                function (Builder $query) use ($missionTemplate) {
                    $query->where('template_id', $missionTemplate->id);
                }
            )->count(),
        ];
    }

    public function update(MissionTemplateUpdateRequest $request, MissionTemplate $missionTemplate)
    {

        if ($request->has('tags')) {
            $tags = collect($request->input('tags'));
            $values = $tags->pluck($tags, 'id')->map(function ($item) {
                return ['field' => 'mission_template_tags'];
            });
            $missionTemplate->tags()->sync($values);
        }

        $missionTemplate->update($request->validated());

        return $missionTemplate;
    }

    public function delete(Request $request, MissionTemplate $missionTemplate)
    {
        $this->authorize('delete', $missionTemplate);

        $relatedMissionsCount = Mission::ofTemplate($missionTemplate->id)->count();

        if ($relatedMissionsCount) {
            abort('422', "Ce modèle est relié à {$relatedMissionsCount} mission(s)");
        }

        return (string) $missionTemplate->delete();
    }
}
