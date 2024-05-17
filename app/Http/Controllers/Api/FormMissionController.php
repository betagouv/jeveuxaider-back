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
use App\Http\Requests\Api\MissionDeleteRequest;
use App\Http\Requests\Api\MissionDuplicateRequest;
use App\Http\Requests\Api\MissionUpdateRequest;
use App\Models\Media;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
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
            'publics_volontaires' => '',
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
}
