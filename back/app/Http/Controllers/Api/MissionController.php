<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Http\Requests\Api\MissionUpdateRequest;
use App\Http\Requests\Api\MissionStructureRequest;
use App\Http\Requests\Api\MissionCloneRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FiltersMissionCeu;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MissionsExport;
use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMissionDates;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionLieu;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionDomaine;
use App\Filters\FiltersMissionPublicsVolontaires;
use App\Filters\FiltersProfileDepartment;
use App\Filters\FiltersProfileSkill;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileZips;
use App\Http\Requests\Api\MissionDeleteRequest;
use App\Models\NotificationTemoignage;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Tag;
use App\Services\ApiEngagement;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedSort;
use Illuminate\Support\Str;
use App\Notifications\NotificationTemoignageCreate;

class MissionController extends Controller
{
    public function promotedToFrontPage(Request $request)
    {
        return Mission::search('')
            ->where('is_priority', true)
            ->with([
                'filters' => 'provider:reserve_civique',
            ])
            ->take(10)
            ->get()
            ->load('domaine', 'template', 'template.domaine', 'template.media', 'structure');
    }

    public function index(Request $request)
    {
        return QueryBuilder::for(Mission::role($request->header('Context-Role')))
            
        // ->with('structure:id,name,state', 'responsable')
            // ->allowedAppends(['domaines', 'participations_validated_count'])
            ->allowedFilters([
                'state',
                // AllowedFilter::custom('search', new FiltersMissionSearch),
                // 'name',
                // 'type',
                // 'structure.statut_juridique',
                // 'structure.state',
                // AllowedFilter::exact('department'),
                // AllowedFilter::exact('template_id'),
                // AllowedFilter::exact('structure_id'),
                // AllowedFilter::exact('is_priority'),
                // AllowedFilter::exact('id'),
                // AllowedFilter::custom('ceu', new FiltersMissionCeu),
                // 
                // AllowedFilter::custom('lieu', new FiltersMissionLieu),
                // AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                // AllowedFilter::custom('dates', new FiltersMissionDates),
                // AllowedFilter::custom('domaine', new FiltersMissionDomaine),
                // AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires),
                // AllowedFilter::exact('responsable_id'),
                // AllowedFilter::scope('minimum_commitment'),
                // AllowedFilter::scope('of_reseau'),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
            // ->allowedSorts(['places_left', 'type'])

    }

    public function export(Request $request)
    {
        return Excel::download(new MissionsExport($request), 'missions.xlsx');
    }

    public function show(Request $request, $id)
    {

        if (is_numeric($id)) {
            $mission = Mission::with(['structure.members:id,first_name,last_name,mobile,email', 'template.domaine', 'domaine', 'tags', 'responsable'])->withCount('temoignages')->where('id', $id)->first();
            if ($mission) {
                $mission->append(['skills', 'domaines', 'domaine_secondaire', 'full_address', 'has_places_left']);
                $mission->structure->append(['logo']);
            }
        } else {
            // API ENGAGEMENT
            $api = new ApiEngagement();
            $mission = $api->getMission($id);
            if ($mission) {
                $mission['isFromApi'] = true;
            }
        }

        if (!$mission) {
            abort(404, 'Cette mission n\'existe pas');
        }

        return $mission['isFromApi'] ? $mission : $mission->append('participations_total', 'full_url');
    }

    public function update(MissionUpdateRequest $request, Mission $mission)
    {
        if ($request->has('domaine_secondaire')) {
            $domaine_id = collect($request->input('domaine_secondaire'))->get('id');
            $domaine_secondaire = Tag::where('id', $domaine_id)->get();
            $mission->syncTagsWithType($domaine_secondaire, 'domaine');
        }

        if ($request->has('skills')) {
            $skills_ids = collect($request->input('skills'))->pluck('id');
            $skills = Tag::whereIn('id', $skills_ids)->get();
            $mission->syncTagsWithType($skills, 'competence');
        }

        $mission->update($request->validated());

        return $mission;
    }

    public function delete(MissionDeleteRequest $request, Mission $mission)
    {
        return (string) $mission->delete();
    }

    public function restore($id)
    {
        $mission = Mission::withTrashed()->findOrFail($id);
        $this->authorize('restore', $mission);
        return (string) $mission->restore();
    }

    public function destroy($id)
    {
        $mission = Mission::withTrashed()->findOrFail($id);
        $this->authorize('destroy', $mission);
        return (string) $mission->forceDelete();
    }

    public function clone(MissionCloneRequest $request, Mission $mission)
    {
        return $mission->clone();
    }

    public function structure(MissionStructureRequest $request, Mission $mission)
    {
        return Structure::with('members')->withCount('missions', 'participations', 'waitingParticipations')->where('id', $mission->structure_id)->first();
    }

    public function benevoles(Request $request, Mission $mission)
    {
        if (!$mission->has_places_left || $mission->state != 'Validée' || $mission->structure->state != 'Validée') {
            abort(403, "Vous n'êtes pas autorisé à accéder à ce contenu");
        }

        $profilesQueryBuilder =  Profile::where('is_visible', true)
            ->withAnyTags($mission->domaines)
            ->whereDoesntHave('participations', function (Builder $query) use ($mission) {
                $query->where('mission_id', $mission->id);
            });

        if ($mission->type == 'Mission en présentiel') {
            $profilesQueryBuilder->department($mission->department);
        }


        return QueryBuilder::for($profilesQueryBuilder)
            ->allowedAppends('last_online_at', 'skills', 'domaines', 'notification_benevole_stats')
            ->allowedFilters(
                AllowedFilter::custom('zips', new FiltersProfileZips),
                AllowedFilter::custom('domaine', new FiltersProfileTag),
                AllowedFilter::custom('department', new FiltersProfileDepartment),
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                AllowedFilter::custom('skills', new FiltersProfileSkill),
                AllowedFilter::scope('minimum_commitment')
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function responsable(MissionStructureRequest $request, Mission $mission)
    {
        return $mission->responsable;
    }

    public function similar(Request $request, Mission $mission)
    {
        // X-sell sur le domain d'action ET la ville
        $query = Mission::search('')
            ->where('id', '!=', $mission->id)
            ->with([
                'facetFilters' => 'domaine_name:' . $mission->domaine_name,
                // Sans prendre en compte l'API, sinon erreur ScoutExtended ObjectID seems invalid
                'filters' => 'provider:reserve_civique',
            ]);
        if ($mission->latitude && $mission->longitude) {
            $query->aroundLatLng($mission->latitude, $mission->longitude);
        }
        return $query->paginate(10)->load('domaine', 'template', 'template.domaine', 'template.media', 'structure');
    }

    public function testimoniesStats(Request $request, Mission $mission)
    {
        return $mission->getTestimoniesStats();
    }

    public function sendTestimonyNotifications(Request $request, Mission $mission)
    {
        // Seulement pour les missions terminées.
        if ($mission->state != "Terminée") {
            abort(403, "La mission doit être terminée !");
        }

        $participations = $mission->participations()->where('state', 'Validée')->get();
        foreach ($participations as $participation) {
            // Skip if notification already exists.
            if (NotificationTemoignage::where('participation_id', $participation->id)->exists()) {
                continue;
            }

            do {
                $token = Str::random(32);
            } while (NotificationTemoignage::where('token', $token)->first());

            $notificationTemoignage = NotificationTemoignage::create([
                'token' => $token,
                'participation_id' => $participation->id,
                'reminders_sent' => 1,
            ]);
            $notificationTemoignage->participation->profile->user->notify(new NotificationTemoignageCreate($notificationTemoignage));
        }

        return $mission->getTestimoniesStats();
    }
}
