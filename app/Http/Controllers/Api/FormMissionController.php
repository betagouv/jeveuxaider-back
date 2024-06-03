<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use App\Rules\AddressesInDepartment;
use App\Rules\AddressIsNeeded;
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
            'participations_max' => 1,
            'user_id' => Auth::guard('api')->user()->id,
        ]);

        $mission->responsables()->attach(Auth::guard('api')->user()->profile->id);

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
            'commitment__period' => '',
            'commitment__time_period' => '',
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
            'department' => 'required_if:type,Mission en présentiel',
            'is_autonomy' => '',
            'autonomy_zips' => ['required_if:is_autonomy,true', new AddressesInDepartment()],
            'autonomy_precisions' => '',
            'address' => '',
            'latitude' => [new AddressIsNeeded()],
            'longitude' => [new AddressIsNeeded()],
            'zip' => [new AddressIsNeeded()],
            'city' => [new AddressIsNeeded()],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

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

        $structureMembersProfilesIds = $mission->structure->members->pluck('profile.id');

        $validator = Validator::make($request->all(), [
            'responsables' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $responsableProfilesIds = collect($request->input('responsables'))->pluck('id');

        foreach ($responsableProfilesIds as $responsableProfileId) {
            if (!$structureMembersProfilesIds->contains($responsableProfileId)) {
                abort(422, "Vous ne pouvez pas ajouter un responsable qui n'est pas membre de l'organisation");
            }
        }

        $values = collect($request->input('responsables'))->pluck('id');
        $mission->responsables()->sync($values);
        $mission->load('responsables.avatar');

        return $mission;
    }
}
