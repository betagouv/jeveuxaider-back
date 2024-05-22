<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

    public function show(Mission $mission)
    {
        $mission->load([
            'structure.members.profile.avatar',
            'template.domaine',
            'template.domaineSecondary',
            'domaine',
            'domaineSecondary',
            // 'responsable.tags',
            // 'responsable.user',
            'responsable.avatar',
            'skills',
            // 'template.photo',
            'illustrations',
            // 'structure.illustrations',
            // 'structure.overrideImage1',
            // 'structure.logo',
            // 'activity:id,name',
            // 'activitySecondary:id,name',
            // 'template.activity:id,name',
            // 'template.activitySecondary:id,name',
            // 'structure.reseaux:id,name',
            // 'tags'
        ]);

        $mission->append(['full_address', 'has_places_left']);
        
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
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'dates' => '',
        ]);

        ray('updateDates', $request->all());
        ray('updateDates validator', $validator->validated());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

        return $mission;
    }

    public function updateLieux(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
            'type' => 'required',
            'department' => 'required',
            'is_autonomy' => '',
            'autonomy_zips' => [
                'required_if:is_autonomy,true',
                function ($attribute, $autonomy_zips, $fail) use ($request){
                    $datas = $request->all();
                    if (!empty($datas['is_autonomy']) && !empty($autonomy_zips) && !empty($datas['department'])) {
                        $department = $datas['department'];
                        foreach ($autonomy_zips as $item) {
                            if (substr($item['zip'], 0, strlen($department)) != $department) {
                                // Exeptions.
                                if (in_array($department, ['2A', '2B']) && substr($item['zip'], 0, 2) == '20') {
                                    continue;
                                }
                                $fail('Les codes postaux et le département ne correspondent pas !');

                                return;
                            }
                        }
                    }
                },
            ],
            'autonomy_precisions' => '',
            'address' => '',
            'latitude' => [
                Rule::requiredIf(function () use ($request){
                    if ($request->get('is_autonomy') === true) {
                        return false;
                    }
                    // Hack - Dom Tom (Nouvelle Calédonie et Polynésie française)
                    if (in_array($request->get('department'), ['987', '988'])) {
                        return false;
                    }
                    if ($request->get('type') == 'Mission en présentiel') {
                        return true;
                    }
                }),
            ],
            'longitude' => [
                Rule::requiredIf(function () use ($request){
                    if ($request->get('is_autonomy') === true) {
                        return false;
                    }
                    // Hack - Dom Tom (Nouvelle Calédonie et Polynésie française)
                    if (in_array($request->get('department'), ['987', '988'])) {
                        return false;
                    }
                    if ($request->get('type') == 'Mission en présentiel') {
                        return true;
                    }
                }),
            ],
            'zip' => [
                Rule::requiredIf(function () use ($request){
                    if ($request->get('type') === 'Mission en présentiel' && $request->get('is_autonomy') === false) {
                        return true;
                    }
                }),
            ],
            'city' => [
                Rule::requiredIf(function () use ($request){
                    if ($request->get('type') === 'Mission en présentiel' && $request->get('is_autonomy') === false) {
                        return true;
                    }
                }),
            ],
            'department' => [
                'requiredIf:type,Mission en présentiel',
                function ($attribute, $department, $fail) use ($request){
                    $datas = $request->all();
                    if (empty($datas['is_autonomy']) && !empty($datas['zip'])) {
                        $zip = str_replace(' ', '', $datas['zip']);

                        if (substr($zip, 0, strlen($department)) != $department) {
                            // Exeptions.
                            if (in_array($department, ['2A', '2B']) && substr($zip, 0, 2) == '20') {
                                return;
                            }

                            $fail("L'adresse et le département ne correspondent pas !");
                        }
                    }
                },
            ],
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
        $mission->load('skills');

        return $mission;
    }

    public function updateResponsables(Request $request, Mission $mission)
    {
        $validator = Validator::make($request->all(),[
            'responsable_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mission->update($validator->validated());

        $mission->load('responsable.avatar');

        return $mission;
    }
}
