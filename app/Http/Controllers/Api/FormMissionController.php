<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FormMissionController extends Controller
{
    public function store(Request $request, Structure $structure)
    {
        $validator = Validator::make($request->all(),[
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
            'participations_max' => 5,
            'user_id' => Auth::guard('api')->user()->id,
            'responsable_id' => Auth::guard('api')->user()->profile->id,
        ]);

        return $mission;
    }

    public function updateTitle(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update([
            'name' => $request->input('name'),
        ]);

        return $mission;
    }

    public function updateVisuel(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
            'media_id' => 'required',
        ], [
            'media_id.required' => 'Vous devez sélectionner une image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->illustrations()->sync([$request->input('media_id') => ['field' => 'mission_illustrations']]);
        $mission->load('illustrations');

        return  $mission;
    }

    public function updateInformations(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
            'domaine_id' => '',
            'domaine_secondary_id' => '',
            'activity_id' => 'required',
            'activity_secondary_id' => '',
            'publics_beneficiaires' => 'required',
            'objectif' => 'required',
            'description' => 'required',
            'information' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

        return $mission;
    }

    public function updateDates(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
            'date_type' => 'required',
            'commitment__duration' => 'required',
            'commitment__period' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

        return $mission;
    }

    public function updateBenevoles(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
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
        $validator = Validator::make($request->all(),[
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

        return $mission;
    }

    public function updateResponsables(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
            'responsables' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ray($request->input('responsables'));

        // $mission->update($validator->validated());

        return $mission;
    }
}
