<?php

namespace App\Http\Controllers\Api;

use App\Exports\ParticipationsExport;
use App\Filters\FiltersParticipationCollectivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Participation;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use App\Filters\FiltersParticipationSearch;
use App\Filters\FiltersParticipationLieu;
use App\Filters\FiltersParticipationDomaine;
use App\Http\Requests\Api\ParticipationCreateRequest;
use App\Http\Requests\Api\ParticipationUpdateRequest;
use App\Http\Requests\Api\ParticipationDeleteRequest;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;

class ParticipationController extends Controller
{
    public function index(Request $request)
    {
        // 455 - 505 queries   600 - 800 ms
        return QueryBuilder::for(Participation::role($request->header('Context-Role'))->with('profile', 'mission', 'mission.structure', 'mission.tuteur'))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch),
                AllowedFilter::custom('lieu', new FiltersParticipationLieu),
                AllowedFilter::custom('domaine', new FiltersParticipationDomaine),
                AllowedFilter::custom('collectivity', new FiltersParticipationCollectivity),
                'state',
                'mission.department',
                'mission.type',
                'mission.name',
                AllowedFilter::exact('mission.template_id'),
                AllowedFilter::exact('mission.id'),
                AllowedFilter::exact('mission.tuteur_id'),
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'))
        ;
    }

    public function show(Request $request, Int $id)
    {
        return Participation::with(['mission', 'profile'])->where('id', $id)->first();
    }

    public function export(Request $request)
    {
        return Excel::download(new ParticipationsExport($request), 'profiles.xlsx');
    }

    public function store(ParticipationCreateRequest $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $participationCount = Participation::where('state', '!=', 'Annulée')->where('profile_id', request("profile_id"))
            ->where('mission_id', request("mission_id"))->count();

        if ($participationCount > 0) {
            abort(402, "Désolé, vous avez déjà participé à cette mission !");
        }

        $mission = Mission::find(request("mission_id"));

        if ($mission && $mission->has_places_left) {
            $participation = Participation::create($request->validated());
            if (request('content')) {
                // En attendant de régler le souci des responsables sans user
                $user = $mission->tuteur->user ?? $mission->structure->user;
                $conversation = $currentUser->startConversation($user, $participation);
                $currentUser->sendMessage($conversation->id, request('content'));
            }
            $mission->update(); // Places left & Algolia
            return $participation;
        }

        abort(402, "Désolé, la mission n'a plus de place disponible !");
    }

    public function update(ParticipationUpdateRequest $request, Participation $participation)
    {
        $participation->update($request->validated());

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        return $participation;
    }

    public function cancel(Request $request, Participation $participation)
    {
        if (Auth::guard('api')->user()->profile->id != $participation->profile_id) {
            abort(403, 'Cette participation ne vous appartient pas');
        }

        $participation->update(['state' => 'Annulée']);

        return $participation;
    }

    public function delete(ParticipationDeleteRequest $request, Participation $participation)
    {
        return (string) $participation->delete();
    }

    public function destroy($id)
    {
        $participation = Participation::withTrashed()->findOrFail($id);
        return (string) $participation->forceDelete();
    }

    public function massValidation(Request $request)
    {
        $participations = Participation::role('responsable')->where('state', 'En attente de validation')->get();
        foreach ($participations as $participation) {
            $participation->update(['state' => 'Validée']);
        }
    }
}
