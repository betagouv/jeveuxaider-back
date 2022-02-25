<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersReseauSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReseauUpdateRequest;
use App\Http\Requests\Api\ReseauUploadRequest;
use App\Http\Requests\ReseauRequest;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Notifications\ReseauNewLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Tag;
use Illuminate\Support\Str;

class ReseauController extends Controller
{

    public function index(Request $request)
    {
        return QueryBuilder::for(Reseau::withCount(['structures', 'missionTemplates', 'missions']))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch),
                AllowedFilter::exact('is_published'),
                AllowedFilter::exact('id'),
                'name',
            ])
            ->allowedIncludes(['illustrations', 'overrideImage1'])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $reseau = (is_numeric($slugOrId))
            ? Reseau::where('id', $slugOrId)
            ->with(['responsables', 'domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2'])
            ->withCount('structures', 'missions', 'missionTemplates', 'invitationsAntennes', 'responsables')
            ->firstOrFail()
            : Reseau::where('slug', $slugOrId)
            ->with(['domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2'])
            ->withCount(['structures' => function ($query) {
                $query->where('state', 'Validée');
            }])
            ->with([
                'structures' => function ($query) {
                    $query->where('state', 'Validée')
                        ->withCount(['missions' => function ($query) {
                            $query->where('state', 'Validée');
                        }])
                        ->orderBy('missions_count', 'DESC')
                        ->limit(5);
                }
            ])
            ->firstOrFail()
            //->append(['domaines_with_image', 'participations_max']);
            ->append(['participations_max']);

        //  return $reseau->append(["domaines", "logo", "override_image_1", "override_image_2"]);
        return $reseau;
    }

    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create(
            $request->validated()
        );

        if ($request->has('domaines')) {
            $domaines_ids = collect($request->input('domaines'))->pluck('id');
            $domaines = Tag::whereIn('id', $domaines_ids)->get();
            $reseau->syncTagsWithType($domaines, 'domaine');
        }

        return $reseau;
    }

    public function update(ReseauUpdateRequest $request, Reseau $reseau)
    {
        if ($request->has('domaines')) {
            $domaines =  collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'reseau_domaines'];
            });
            $reseau->domaines()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations =  collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'reseau_illustrations'];
            });
            $reseau->illustrations()->sync($values);
        }

        return $reseau->update($request->validated());
    }

    public function attachOrganisations(Request $request, Reseau $reseau)
    {
        if ($request->input('organisations')) {
            $reseau->structures()->syncWithoutDetaching($request->input('organisations'));
        }

        return $reseau;
    }

    public function lead(Request $request)
    {
        Notification::route('mail', [
            'nassim.merzouk@beta.gouv.fr' => 'Joe',
            'joe.achkar@beta.gouv.fr' => 'Nassim',
        ])->notify(new ReseauNewLead($request->all()));

        return true;
    }

    public function delete(Request $request, Reseau $reseau)
    {
        $this->authorize('update', $reseau);

        return (string) $reseau->delete();
    }

    public function responsables(Request $request, Reseau $reseau)
    {
        return $reseau->responsables()->orderBy('id')->get();
    }

    public function invitationsResponsables(Request $request, Reseau $reseau)
    {
        return $reseau->invitationsResponsables()->orderBy('id')->get();
    }

    public function deleteResponsable(Request $request, Reseau $reseau, Profile $responsable)
    {
        $reseau->deleteResponsable($responsable);
        return $reseau->responsables;
    }

    public function structures(Request $request, Reseau $reseau)
    {
        return $reseau->structures()
            ->where('state', 'Validée')
            ->where('statut_juridique', 'Association')
            ->orderByRaw('UPPER(structures.city)') // Bypass case insensitive collation (PostgreSQL)
            ->get();
    }
}
