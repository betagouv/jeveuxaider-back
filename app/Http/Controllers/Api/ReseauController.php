<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersReseauSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddResponsableRequest;
use App\Http\Requests\Api\ReseauUpdateRequest;
use App\Http\Requests\ReseauRequest;
use App\Models\Invitation;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Notifications\ReseauNewLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ReseauController extends Controller
{

    public function index(Request $request)
    {
        $results = QueryBuilder::for(Reseau::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch),
                AllowedFilter::exact('is_published'),
                AllowedFilter::exact('id'),
                'name',
            ])
            ->allowedIncludes(['illustrations', 'overrideImage1','missions','structures'])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        if ($request->has('append')) {
            $results->append($request->input('append'));
        }

        return $results;
    }

    public function show($slugOrId)
    {

        if (is_numeric($slugOrId)) {
            return Reseau::where('id', $slugOrId)
            ->with(['responsables', 'domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2', 'illustrationsAntennes'])
            ->withCount('structures', 'missions', 'missionTemplates', 'invitationsAntennes', 'responsables')
            ->firstOrFail()->append(['missing_fields', 'completion_rate']);
        }

        return Reseau::where('slug', $slugOrId)
            ->with(['domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2', 'illustrationsAntennes'])
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
            ->append(['participations_max']);
    }

    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create(
            $request->validated()
        );

        if ($request->has('domaines')) {
            $domaines =  collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'reseau_domaines'];
            });
            $reseau->domaines()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations =  collect(array_filter($request->input('illustrations')));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'reseau_illustrations'];
            });
            $reseau->illustrations()->sync($values);
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

    public function responsables(Request $request, Reseau $reseau)
    {
        return $reseau->responsables()->orderBy('id')->get();
    }

    public function invitationsResponsables(Request $request, Reseau $reseau)
    {
        return $reseau->invitationsResponsables()->orderBy('id')->get();
    }

    public function addResponsable(AddResponsableRequest $request, Reseau $reseau)
    {
        $profile = Profile::whereEmail($request->input('email'))->first();
        $profile->update(['tete_de_reseau_id' => $reseau->id]);
        $profile->user->resetContextRole();

        return $reseau->responsables;
    }

    public function deleteResponsable(Request $request, Reseau $reseau, Profile $responsable)
    {
        $this->authorize('update', $reseau);
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

    public function delete(Request $request, Reseau $reseau)
    {

        $relatedStructuresCount = Structure::ofReseau($reseau->id)->count();
        $relatedInvitationsCount = Invitation::ofReseau($reseau->id)->count();
        $relatedInvitationsAntennesCount = Invitation::ofReseauAndRoleAntenne($reseau->id)->count();

        if ($relatedStructuresCount) {
            abort('422', "Ce réseau est relié à {$relatedStructuresCount} antenne(s)");
        }

        if ($relatedInvitationsCount) {
            abort('422', "Ce réseau est relié à {$relatedInvitationsCount} invitation(s) de responsable");
        }

        if ($relatedInvitationsAntennesCount) {
            abort('422', "Ce réseau est relié à {$relatedInvitationsAntennesCount} invitation(s) d'antenne");
        }

        $reseau->responsables->map(function ($responsable) use ($reseau) {
            $reseau->deleteResponsable($responsable);
        });

        return (string) $reseau->delete();
    }
}
