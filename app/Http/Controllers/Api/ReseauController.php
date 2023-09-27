<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersReseauSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddResponsableRequest;
use App\Http\Requests\Api\ReseauUpdateRequest;
use App\Http\Requests\ReseauRequest;
use App\Models\Invitation;
use App\Models\Reseau;
use App\Models\Structure;
use App\Models\User;
use App\Notifications\ReseauNewLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;

class ReseauController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(Reseau::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch()),
                AllowedFilter::exact('is_published'),
                AllowedFilter::exact('id'),
                'name',
            ])
            ->allowedIncludes(['illustrations', 'overrideImage1', 'missions', 'structures'])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        if ($request->has('append')) {
            $results->append(explode(',', $request->input('append')));
        }

        return $results;
    }

    public function show($slugOrId)
    {
        if (is_numeric($slugOrId)) {
            return Reseau::where('id', $slugOrId)
                ->with(['responsables.profile.tags', 'responsables.profile.user', 'domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2', 'illustrationsAntennes'])
                ->withCount('structures', 'missions', 'missionTemplates', 'invitationsAntennes', 'responsables')
                ->firstOrFail()->append(['missing_fields', 'completion_rate']);
        }

        return Reseau::where('slug', $slugOrId)
            ->with(['domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2', 'illustrationsAntennes'])
            ->withCount(['missionsAvailable', 'participations'])
            ->firstOrFail()
            ->append(['places_left', 'statistics']);
    }

    public function activities(Request $request, Reseau $reseau)
    {
        $results = DB::select(
            "
                SELECT activities.id, activities.name, COUNT(*) FROM activities
                LEFT JOIN missions ON missions.activity_id = activities.id OR missions.activity_secondary_id = activities.id
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = missions.structure_id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE reseaux.id = :reseau
                AND missions.state IN ('Validée', 'Terminée')
                AND structures.state IN ('Validée')
                GROUP BY activities.id
                ORDER BY COUNT(*) DESC
            ",
            [
                'reseau' => $reseau->id,
            ]
        );

        return $results;
    }

    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create(
            $request->validated()
        );

        if ($request->has('domaines')) {
            $domaines = collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'reseau_domaines'];
            });
            $reseau->domaines()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations = collect(array_filter($request->input('illustrations')));
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
            $domaines = collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'reseau_domaines'];
            });
            $reseau->domaines()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations = collect($request->input('illustrations'));
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
            'nivine.katanji@beta.gouv.fr' => 'Nivine',
            'joe.achkar@beta.gouv.fr' => 'Joe',
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
        $user = User::whereEmail($request->input('email'))->first();

        if ($user && $user->reseaux->count() > 0) {
            abort(422, 'Cet email est déjà rattaché à un réseau');
        }

        $reseau->addResponsable($user);

        return $reseau->responsables;
    }

    public function deleteResponsable(Request $request, Reseau $reseau, User $responsable)
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

        $reseau->responsables->map(function (User $responsable) use ($reseau) {
            $reseau->deleteResponsable($responsable);
        });

        return (string) $reseau->delete();
    }
}
