<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersDisponibility;
use App\Filters\FiltersDoesntHaveTags;
use App\Filters\FiltersMissionDate;
use App\Filters\FiltersMissionIsTemplate;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionPriorityAvailable;
use App\Filters\FiltersMissionPublicsBeneficiaires;
use App\Filters\FiltersMissionPublicsVolontaires;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersTags;
use App\Filters\FiltersMissionZip;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MissionUpdateRequest;
use App\Jobs\NotifyResponsablesMissionUserWaitingListCreated;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\MissionShared;
use App\Services\ApiEngagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MissionController extends Controller
{
    // public function prioritaires(Request $request)
    // {
    //     return  QueryBuilder::for(Mission::where('is_priority', true))
    //         ->with(['domaine', 'template', 'template.domaine', 'template.photo', 'illustrations', 'structure'])
    //         ->allowedFilters([
    //             AllowedFilter::custom('search', new FiltersMissionSearch()),
    //             AllowedFilter::custom('available', new FiltersMissionPriorityAvailable()),
    //         ])
    //         ->defaultSort('-created_at')
    //         ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    // }

    public function index(Request $request)
    {
        $result = QueryBuilder::for(Mission::role($request->header('Context-Role')))
            ->with(['domaine', 'template', 'template.domaine', 'structure'])
            ->allowedFilters([
                AllowedFilter::exact('state'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('department'),
                AllowedFilter::custom('zip', new FiltersMissionZip()),
                // AllowedFilter::exact('responsables.id'),
                // AllowedFilter::exact('responsables.email'),
                AllowedFilter::exact('template.id'),
                AllowedFilter::exact('structure.id'),
                AllowedFilter::exact('structure.name'),
                AllowedFilter::exact('structure.statut_juridique'),
                AllowedFilter::exact('structure.reseaux.id'),
                AllowedFilter::exact('structure.reseaux.name'),
                AllowedFilter::exact('is_snu_mig_compatible'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofTemplate'),
                AllowedFilter::scope('ofStructure'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('ofActivity'),
                AllowedFilter::scope('hasActivity'),
                AllowedFilter::scope('hasTemplate'),
                AllowedFilter::scope('hasCreneaux'),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft()),
                AllowedFilter::custom('date', new FiltersMissionDate()),
                AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires()),
                AllowedFilter::custom('publics_beneficiaires', new FiltersMissionPublicsBeneficiaires()),
                AllowedFilter::custom('search', new FiltersMissionSearch()),
                AllowedFilter::scope('available'),
                AllowedFilter::custom('is_template', new FiltersMissionIsTemplate()),
                AllowedFilter::exact('is_autonomy'),
                AllowedFilter::custom('tags', new FiltersTags()),
                AllowedFilter::custom('no_tags', new FiltersDoesntHaveTags()),
                AllowedFilter::exact('is_online'),
                AllowedFilter::scope('ofResponsable'),
                AllowedFilter::exact('is_registration_open'),
            ])
            ->allowedIncludes([
                'template.photo',
                'illustrations',
                AllowedInclude::count('participationsCount'),
                'tags',
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                'updated_at',
                'places_left',
            ])
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $result->append('has_places_left');

        return $result;
    }

    public function view(Request $request, $slugOrId)
    {
        if (is_numeric($slugOrId)) {
            $mission = Mission::with([
                'structure:id,name,state,statut_juridique,description,slug',
                'template:id,title,subtitle,description,objectif,domaine_id,domaine_secondary_id,activity_id,activity_secondary_id',
                'template.domaine:id,name,slug',
                'template.domaineSecondary:id,name,slug',
                'domaine:id,name,slug',
                'responsables:id,first_name,last_name',
                'domaineSecondary:id,name,slug',
                'template.photo',
                'illustrations',
                'structure.logo',
                'activity:id,name',
                'activitySecondary:id,name',
                'template.activity:id,name',
                'template.activitySecondary:id,name',
            ])->where('id', $slugOrId)->first();
            if ($mission) {
                $mission->append(['full_address', 'has_places_left']);
            }
        } else {
            // API ENGAGEMENT
            $api = new ApiEngagement();
            $mission = $api->getMission($slugOrId);
            if ($mission) {
                $mission['isFromApi'] = true;
            }
        }

        if (!$mission) {
            abort(404, 'Cette mission n\'existe pas');
        }

        return $mission['isFromApi'] ? $mission : $mission->append('participations_total', 'full_url');
    }

    public function show(Request $request, Mission $mission)
    {
        $mission->load([
            'structure.members',
            'template.domaine',
            'template.domaineSecondary',
            'domaine',
            'domaineSecondary',
            'responsables',
            'skills',
            'template.photo',
            'illustrations',
            'structure.illustrations',
            'structure.overrideImage1',
            'structure.logo',
            'activity:id,name',
            'activitySecondary:id,name',
            'template.activity:id,name',
            'template.activitySecondary:id,name',
            'structure.reseaux:id,name',
            'tags'
        ]);

        $mission->append(['full_address', 'has_places_left','participations_total', 'full_url']);

        return $mission;
    }

    public function update(MissionUpdateRequest $request, Mission $mission)
    {
        if ($request->has('tags')) {
            $tags = collect($request->input('tags'));
            $values = $tags->pluck($tags, 'id')->map(function ($item) {
                return ['field' => 'mission_tags'];
            });
            $mission->tags()->sync($values);
        }

        $mission->update($request->validated());

        return $mission;
    }

    public function delete(Request $request, Mission $mission)
    {
        $this->authorize('delete', $mission);

        $relatedParticipationsCount = Participation::where('mission_id', $mission->id)->count();

        if ($relatedParticipationsCount) {
            abort('422', "Cette mission est reliée à {$relatedParticipationsCount} participation(s)");
        }

        return (string) $mission->delete();
    }

    public function duplicate(Request $request, Mission $mission)
    {
        $this->authorize('duplicate', $mission);

        if ($mission->template_id && (!$mission->template->published || $mission->template->state !== 'validated')) {
            abort('422', "Le modèle de cette mission n'est plus disponible.");
        }

        $new = $mission->duplicate();

        activity()
            ->causedBy($request->user())
            ->performedOn($new)
            ->withProperties(['attributes' => ['from_id' => $mission->id]])
            ->event('duplicated')
            ->log('duplicated');

        return $new;
    }

    public function share(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array|max:5',
            'emails.*' => 'email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if(!$mission->isAvailableForRegistration) {
            return response()->json(['message' => 'La mission n\'est plus ouverte aux inscriptions'], 422);
        }

        $emails = collect($request->input('emails'));
        $user = User::find($request->user()->id);

        $emails->each(function ($email) use ($mission, $user) {
            Notification::route('mail', $email)->notify(new MissionShared($mission, $user));
        });

        return response()->json(['message' => 'La mission a bien été partagée']);
    }

    public function benevoles(Request $request, Mission $mission)
    {
        if ($request->header('Context-Role') !== 'admin') {
            if (!$mission->has_places_left) {
                abort(401, "Plus aucune place de disponible pour cette mission");
            }
            if ($mission->state != 'Validée') {
                abort(401, "Votre mission doit être validée pour accéder à cette fonctionnalité");
            }
            if ($mission->structure->state != 'Validée') {
                abort(401, "Votre organisation doit être validée pour accéder à cette fonctionnalité");
            }
        }


        $domaineId = $mission->template_id ? $mission->template->domaine_id : $mission->domaine_id;

        $profilesQueryBuilder = Profile::select([
            'id',
            'user_id',
            'first_name',
            'last_name',
            'zip',
            'disponibilities',
            'commitment',
            'type',
            'is_visible'
        ])
            ->where('is_visible', true)
            ->whereHas('user', function (Builder $query) {
                $query->canReceiveNotifications();
            })
            // ->ofDomaine($domaineId)
            ->whereDoesntHave('participations', function (Builder $query) use ($mission) {
                $query->where('mission_id', $mission->id);
            })
        ;

        // if ($mission->type == 'Mission en présentiel') {
        //     $profilesQueryBuilder->department($mission->department);
        // }

        $results = QueryBuilder::for($profilesQueryBuilder)
            ->allowedIncludes([
                'user',
                'participationsValidatedCount',
                'avatar',
                'notificationsBenevoles'
            ])
            ->allowedFilters(
                'zip',
                'commitment',
                AllowedFilter::custom('disponibilities', new FiltersDisponibility()),
                // AllowedFilter::custom('zips', new FiltersProfileZips),
                // AllowedFilter::custom('domaine', new FiltersProfileTag),
                // AllowedFilter::custom('skills', new FiltersProfileSkill),
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));

        $results->getCollection()->transform(function ($profile) {
            return $profile->makeHidden(['last_name', 'full_name']);
        });

        return $results;
    }

    public function similar(Request $request, Mission $mission)
    {

        $activity = $mission->template?->activity?->name ?? $mission->activity?->name;
        $domaine = $mission->template?->domaine?->name ?? $mission->domaine?->name;
        $facetFilters = $activity ? 'activity.name:' . $activity : ($domaine ? 'domaines:' . $domaine : '');

        $query = Mission::search('')
            ->where('id', '!=', $mission->id)
            ->with([
                'facetFilters' => $facetFilters,
                // Sans prendre en compte l'API, sinon erreur ScoutExtended ObjectID seems invalid
                'filters' => 'provider:reserve_civique AND is_registration_open=1 AND has_places_left=1 AND is_outdated=0',
            ]);
        if ($mission->addresses && isset($mission->addresses[0])) {
            $query->aroundLatLng($mission->addresses[0]['latitude'], $mission->addresses[0]['longitude']);
        }

        return $query->paginate(10)->load('domaine', 'template', 'template.domaine', 'template.media', 'structure:id,name,statut_juridique', 'illustrations', 'template.activity');
    }

    public function similarForApi(Request $request)
    {
        $mission = request('mission');
        $facetFilters = '';
        if ($mission['activity']) {
            $facetFilters = 'activity.name:' . $mission['activity']['name'];
        } elseif ($mission['domaine']) {
            $facetFilters = 'domaines:' . $mission['domaine']['name'];
        }

        $query = Mission::search('')
            ->with([
                'facetFilters' => $facetFilters,
                // Sans prendre en compte l'API, sinon erreur ScoutExtended ObjectID seems invalid
                'filters' => 'provider:reserve_civique AND is_registration_open=1 AND has_places_left=1 AND is_outdated=0',
            ]);
        if ($mission['_geoloc']) {
            $query->aroundLatLng($mission['_geoloc']['lat'], $mission['_geoloc']['lng']);
        }

        return $query->paginate(10)->load('domaine', 'template', 'template.domaine', 'template.media', 'structure', 'illustrations', 'template.activity');
    }

    public function publish(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        if ($mission->state != 'Brouillon') {
            abort('422', "La mission ne peut être publiée que si elle est en brouillon");
        }

        if ($mission->template_id && $mission->structure->state == 'Validée') {
            $mission->update(['state' => 'Validée']);
        } else {
            $mission->update(['state' => 'En attente de validation']);
        }

        return $mission;
    }

    public function addUserToWaitingList(Request $request, Mission $mission)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        if($currentUser->waitingListMissions()->where('mission_id', $mission->id)->exists()) {
            abort('422', "Vous avez déjà une alerte pour cette mission");
        }

        $currentUser->waitingListMissions()->attach($mission->id);

        NotifyResponsablesMissionUserWaitingListCreated::dispatch($mission);

        return response()->json(['message' => 'Une alerte a bien été créée pour cette mission']);
    }

    public function removeUserToWaitingList(Request $request, Mission $mission)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        if(!$currentUser->waitingListMissions()->where('mission_id', $mission->id)->exists()) {
            abort('422', "Vous n'avez déjà une alerte pour cette mission");
        }

        $currentUser->waitingListMissions()->detach($mission->id);

        return response()->json(['message' => 'L\'alerte a bien été supprimée pour cette mission']);
    }

}
