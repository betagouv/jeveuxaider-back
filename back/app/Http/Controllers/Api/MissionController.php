<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Http\Requests\Api\MissionUpdateRequest;
use App\Http\Requests\Api\MissionStructureRequest;
use App\Http\Requests\Api\MissionCloneRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MissionsExport;
use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionPublicsVolontaires;
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
use Illuminate\Support\Str;
use App\Notifications\NotificationTemoignageCreate;

class MissionController extends Controller
{
    public function prioritaires(Request $request)
    {
        return  QueryBuilder::for(Mission::where('is_priority', true))
            ->with(['domaine', 'template', 'template.domaine', 'template.media', 'structure'])
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersMissionSearch),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    }

    public function index(Request $request)
    {
        return QueryBuilder::for(Mission::role($request->header('Context-Role')))
            ->with(['domaine', 'template', 'template.domaine', 'structure'])
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('department'),
                AllowedFilter::exact('responsable.id'),
                AllowedFilter::exact('template_id'),
                AllowedFilter::exact('structure.name'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('domaine'),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::scope('available'),
            ])
            ->allowedIncludes(['template.photo'])
            ->defaultSort('-created_at')
            ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    }

    // public function export(Request $request)
    // {
    //     return Excel::download(new MissionsExport($request), 'missions.xlsx');
    // }

    public function show(Request $request, $id)
    {

        if (is_numeric($id)) {
            $mission = Mission::with(['structure.members:id,first_name,last_name,mobile,email', 'template.domaine', 'domaine', 'domaineSecondary', 'responsable', 'skills', 'illustrations'])->withCount('temoignages')->where('id', $id)->first();
            if ($mission) {
                $mission->append(['full_address', 'has_places_left']);
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
        if ($request->has('skills')) {
            $skills =  collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'mission_skills'];
            });
            $mission->skills()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations =  collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'mission_illustrations'];
            });
            $mission->illustrations()->sync($values);
        }

        $mission->update($request->validated());
        return $mission;
    }

    // public function delete(MissionDeleteRequest $request, Mission $mission)
    // {
    //     return (string) $mission->delete();
    // }

    // public function restore($id)
    // {
    //     $mission = Mission::withTrashed()->findOrFail($id);
    //     $this->authorize('restore', $mission);
    //     return (string) $mission->restore();
    // }

    // public function destroy($id)
    // {
    //     $mission = Mission::withTrashed()->findOrFail($id);
    //     $this->authorize('destroy', $mission);
    //     return (string) $mission->forceDelete();
    // }

    // public function clone(MissionCloneRequest $request, Mission $mission)
    // {
    //     return $mission->clone();
    // }

    // public function structure(MissionStructureRequest $request, Mission $mission)
    // {
    //     return Structure::with('members')->withCount('missions', 'participations', 'waitingParticipations')->where('id', $mission->structure_id)->first();
    // }

    public function benevoles(Request $request, Mission $mission)
    {
        if ($request->header('Context-Role') !== 'admin' && (!$mission->has_places_left || $mission->state != 'Validée' || $mission->structure->state != 'Validée')) {
            abort(401, "Vous n'êtes pas autorisé à accéder à ce contenu");
        }

        $domaineId = $mission->template_id ? $mission->template->domaine_id : $mission->domaine_id;

        $profilesQueryBuilder = Profile::where('is_visible', true)
            ->whereHas('user', function (Builder $query) {
                $query->whereNull('anonymous_at');
            })
            ->domaine($domaineId)
            ->whereDoesntHave('participations', function (Builder $query) use ($mission) {
                $query->where('mission_id', $mission->id);
            });

        if ($mission->type == 'Mission en présentiel') {
            $profilesQueryBuilder->department($mission->department);
        }


        return QueryBuilder::for($profilesQueryBuilder)
            ->allowedIncludes([
                'user',
                'participationsValidatedCount',
                'avatar',
            ])
            ->allowedFilters(
                'zip',
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                AllowedFilter::scope('minimum_commitment')
                // AllowedFilter::custom('zips', new FiltersProfileZips),
                // AllowedFilter::custom('domaine', new FiltersProfileTag),
                // AllowedFilter::custom('skills', new FiltersProfileSkill),
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    // public function responsable(MissionStructureRequest $request, Mission $mission)
    // {
    //     return $mission->responsable;
    // }

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

    // public function testimoniesStats(Request $request, Mission $mission)
    // {
    //     return $mission->getTestimoniesStats();
    // }

    // public function sendTestimonyNotifications(Request $request, Mission $mission)
    // {
    //     // Seulement pour les missions terminées.
    //     if ($mission->state != "Terminée") {
    //         abort(403, "La mission doit être terminée !");
    //     }

    //     $participations = $mission->participations()->where('state', 'Validée')->get();
    //     foreach ($participations as $participation) {
    //         // Skip if notification already exists.
    //         if (NotificationTemoignage::where('participation_id', $participation->id)->exists()) {
    //             continue;
    //         }

    //         do {
    //             $token = Str::random(32);
    //         } while (NotificationTemoignage::where('token', $token)->first());

    //         $notificationTemoignage = NotificationTemoignage::create([
    //             'token' => $token,
    //             'participation_id' => $participation->id,
    //             'reminders_sent' => 1,
    //         ]);
    //         $notificationTemoignage->participation->profile->user->notify(new NotificationTemoignageCreate($notificationTemoignage));
    //     }

    //     return $mission->getTestimoniesStats();
    // }
}
