<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\RecomputeConversationUsersWhenMissionResponsablesAdded;
use App\Jobs\RecomputeConversationUsersWhenMissionResponsablesRemoved;
use App\Models\Mission;
use App\Models\Structure;
use App\Rules\AddressesInSameDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FormMissionController extends Controller
{
    public function store(Request $request, Structure $structure)
    {
        $this->authorize('createMission', $structure);

        $validator = Validator::make($request->all(), [
            'domaine_id' => 'required',
            'template_id' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission = $structure->missions()->create([
            'state' => 'Brouillon',
            'domaine_id' => $request->input('domaine_id'),
            'template_id' => $request->input('template_id'),
            'user_id' => Auth::guard('api')->user()->id,
        ]);

        return $mission;
    }

    public function show(Mission $mission)
    {
        $this->authorize('update', $mission);

        $mission->load([
            'structure.members.profile.avatar',
            'domaine',
            'domaineSecondary',
            'responsables.avatar',
            'skills',
            'template',
            'illustrations',
            'tags',
        ]);

        $mission->append(['full_address', 'has_places_left', 'picture']);

        return $mission;
    }

    public function updateTitle(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

        return $mission;
    }

    public function updateVisuel(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $validator = Validator::make($request->all(), [
            'media_id' => 'required',
        ], [
            'media_id.required' => 'Vous devez sélectionner une image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->illustrations()->sync([$request->input('media_id') => ['field' => 'mission_illustrations']]);
        $mission->load('illustrations');
        $mission->append(['picture']);

        return  $mission;
    }

    public function updateInformations(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $validator = Validator::make($request->all(), [
            'domaine_id' => '',
            'domaine_secondary_id' => '',
            'activity_id' => 'required',
            'activity_secondary_id' => '',
            'publics_beneficiaires' => 'required',
            'objectif' => 'required',
            'description' => 'required',
            'information' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

        return $mission;
    }

    public function updateDates(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $validator = Validator::make($request->all(), [
            'date_type' => 'required',
            'commitment__duration' => 'required',
            'commitment__time_period' => 'required_if:date_type,recurring',
            'commitment__duration_min' => 'required_if:date_type,recurring',
            'recurrent_description' => '',
            'with_dates' => 'required|in:yes,no',
            'start_date' => 'nullable|required_if:with_dates,no|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dates' => 'nullable|array|required_if:with_dates,yes',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->input('with_dates') === 'yes') {
            $dates = array_column($request->input('dates'), 'date');
            $carbonDates = array_map(function ($date) {
                return Carbon::parse($date);
            }, $dates);
            usort($carbonDates, function ($a, $b) {
                return $a->lt($b) ? -1 : 1;
            });
            $firstDate = $carbonDates[0];
            $lastDate = $carbonDates[count($carbonDates) - 1];
            $attributes = [
                ...$validator->validated(),
                'start_date' => $firstDate->format('Y-m-d'),
                'end_date' => $lastDate->format('Y-m-d'),
            ];
        } else {
            $attributes = [
                ...$validator->validated(),
                'dates' => null,
            ];
        }

        $mission->update($attributes);

        return $mission;
    }

    public function updateLieux(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'addresses' => $request->input('type') == 'Mission en présentiel' ? [
                'required',
                'array',
                'min:1',
                new AddressesInSameDepartment()
            ] : ''
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($request->input('type') == 'Mission en présentiel'){
            $department = $request->input('addresses')[0]['department'];
            $mission->update([
                ...$validator->validated(),
                'department' => $department
            ]);
        } else {
            $mission->update($validator->validated());
        }

        return $mission;
    }

    public function updateBenevoles(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $validator = Validator::make($request->all(), [
            'participations_max' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

        return $mission;
    }

    public function updateBenevolesInformations(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $validator = Validator::make($request->all(), [
            'publics_volontaires' => '',
            'prerequisites' => 'max:3|array',
            'prerequisites.*' => 'string|max:100',
            'skills' => '',
            'is_motivation_required' => '',
            'is_snu_mig_compatible' => '',
            'snu_mig_places' => 'required_if:is_snu_mig_compatible,true',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('skills')) {
            $skills = collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'mission_skills'];
            });
            $mission->skills()->sync($values);
        }

        $mission->update($validator->validated());
        $mission->load('skills');

        return $mission;
    }

    public function updateResponsables(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $structureUserIds = $mission->structure->members->pluck('id');

        $validator = Validator::make($request->all(), [
            'responsables' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $newResponsableProfileIds = collect($request->input('responsables'))->pluck('id')->toArray();
        $newResponsableUserIds = collect($request->input('responsables'))->pluck('user_id')->toArray();
        $oldResponsableUserIds = $mission->responsables->pluck('user_id')->toArray();

        foreach ($newResponsableUserIds as $userId) {
            if (!$structureUserIds->contains($userId)) {
                abort(422, "Vous ne pouvez pas ajouter un responsable qui n'est pas membre de l'organisation");
            }
        }

        $addedResponsableUserIds = array_diff($newResponsableUserIds, $oldResponsableUserIds);
        if(!empty($addedResponsableUserIds)) {
            RecomputeConversationUsersWhenMissionResponsablesAdded::dispatch($mission, $addedResponsableUserIds);
        }

        $removedResponsableUserIds = array_diff($oldResponsableUserIds, $newResponsableUserIds);
        if(!empty($removedResponsableUserIds)) {
            RecomputeConversationUsersWhenMissionResponsablesRemoved::dispatch($mission, $removedResponsableUserIds);
        }

        $mission->responsables()->sync($newResponsableProfileIds);
        $mission->load('responsables.avatar');

        return $mission;
    }
}
