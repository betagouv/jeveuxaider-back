<?php

namespace App\Http\Controllers\API;

use App\Exports\ParticipationsExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Participation;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use App\Filters\FiltersParticipationSearch;
use App\Filters\FiltersParticipationLieu;
use App\Http\Requests\Api\ParticipationCreateRequest;
use App\Http\Requests\Api\ParticipationUpdateRequest;
use App\Http\Requests\Api\ParticipationDeleteRequest;
use App\Models\Mission;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;

class ParticipationController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Participation::role($request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch),
                AllowedFilter::custom('lieu', new FiltersParticipationLieu),
                'state',
                'mission.department',
                'mission.type',
                'mission.name'
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'))
        ;
    }

    public function export(Request $request)
    {
        return Excel::download(new ParticipationsExport($request), 'profiles.xlsx');
    }

    public function store(ParticipationCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $participationCount = Participation::where('profile_id', request("profile_id"))
            ->where('mission_id', request("mission_id"))->count();

        if ($participationCount > 0) {
            abort(402, "Désolé, vous avez déjà participé à cette mission !");
        }

        $mission = Mission::find(request("mission_id"));

        if ($mission && $mission->has_places_left) {
            $participation = Participation::create($request->validated());
            return $participation;
        }

        abort(402, "Désolé, la mission n'a plus de place disponible !");
    }

    public function update(ParticipationUpdateRequest $request, Participation $participation)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $participation->update($request->validated());

        return $participation;
    }

    public function cancel(Request $request, Participation $participation)
    {
        if (Auth::guard('api')->user()->profile->id != $participation->profile_id) {
            abort(403, 'Cette participation ne vous appartient pas');
        }

        $participation->update(['state' => 'Candidature annulée']);

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
            $participation->update(['state' => 'Mission validée']);
        }
    }
}
