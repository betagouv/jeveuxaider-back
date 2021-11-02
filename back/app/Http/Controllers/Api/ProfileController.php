<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Requests\Api\ProfileUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfilesExport;
use App\Exports\ProfilesReferentsDepartementsExport;
use App\Exports\ProfilesReferentsRegionsExport;
use App\Exports\ProfilesTetesDeReseauExport;
use App\Exports\ProfilesResponsablesExport;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersMatchMission;
use App\Filters\FiltersProfilePostalCode;
use App\Filters\FiltersDisponibility;
use App\Filters\FiltersProfileDepartment;
use App\Filters\FiltersProfileSkill;
use App\Filters\FiltersProfileZips;
use App\Http\Requests\ProfileRequest;
use App\Jobs\NotifyUserOfCompletedExport;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Tag;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Profile::role($request->header('Context-Role'))->with(['structures:name,id', 'territoires:name,id']))
            ->allowedAppends('last_online_at', 'roles', 'has_user', 'skills', 'domaines', 'tete_de_reseau_waiting_actions', 'referent_waiting_actions', 'referent_region_waiting_actions', 'responsable_waiting_actions')
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('postal_code', new FiltersProfilePostalCode),
                AllowedFilter::custom('zips', new FiltersProfileZips),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::custom('domaines', new FiltersProfileTag),
                AllowedFilter::custom('department', new FiltersProfileDepartment),
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                AllowedFilter::custom('skills', new FiltersProfileSkill),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations),
                AllowedFilter::exact('referent_department'),
                'referent_region'
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function participations(Request $request, Profile $profile)
    {
        return QueryBuilder::for(Participation::with(['mission.structure','conversation'])->where('profile_id', $profile->id))
            ->allowedFilters(
                'state'
            )
            ->defaultSort('-created_at')
            ->paginate(8);
    }

    public function statistics(Request $request, Profile $profile)
    {
        return [
            'participations' => [
                'Toutes' => Participation::where('profile_id', $profile->id)->count(),
                'En attente de validation' => Participation::where('profile_id', $profile->id)->where('state', 'En attente de validation')->count(),
                'En cours de traitement' => Participation::where('profile_id', $profile->id)->where('state', 'En cours de traitement')->count(),
                'Validée' => Participation::where('profile_id', $profile->id)->where('state', 'Validée')->count(),
                'Refusée' => Participation::where('profile_id', $profile->id)->where('state', 'Refusée')->count(),
                'Annulée' => Participation::where('profile_id', $profile->id)->where('state', 'Annulée')->count(),
            ]
        ];
    }

    public function export(Request $request)
    {

        $currentUser = User::find(Auth::guard('api')->user()->id);

        if(!$currentUser->profile->can_export_profiles){
            return response()->json(['message'=> 'Seuls les référents accrédités peuvent générer cet export'], 401);
        }

        $folder = 'public/'. config('app.env').'/exports/'.$request->user()->id . '/';
        $fileName = 'profiles-' . Str::random(8) . '.csv';
        $filePath = $folder . $fileName;

        (new ProfilesExport($request->header('Context-Role')))
            ->queue($filePath, 's3')
            ->chain([
                new NotifyUserOfCompletedExport($request->user(), $filePath),
            ]);

        return response()->json(['message'=> 'Export en cours...'], 200);
    }

    public function exportReferentsDepartements(Request $request)
    {
        return Excel::download(new ProfilesReferentsDepartementsExport(), 'referents-departements.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportReferentsRegions(Request $request)
    {
        return Excel::download(new ProfilesReferentsRegionsExport(), 'referents-regions.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportTetesDeReseau(Request $request)
    {
        return Excel::download(new ProfilesTetesDeReseauExport(), 'tetes-de-reseau.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportResponsables(Request $request)
    {
        return Excel::download(new ProfilesResponsablesExport(), 'responsables.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function show(ProfileRequest $request, Profile $profile = null)
    {
        return Profile::with(['structures:id,name,state,statut_juridique','territoires'])->find($profile->id)->append('roles', 'has_user', 'skills', 'domaines')
            ?: Profile::with(['structures:id,name,state,statut_juridique','territoires'])->find($request->user()->profile->id)->append('roles', 'has_user', 'skills', 'domaines');
    }

    public function update(ProfileUpdateRequest $request, Profile $profile = null)
    {
        $profile->update($request->validated());

        if ($request->has('domaines')) {
            $domaines_ids = collect($request->input('domaines'))->pluck('id');
            $domaines = Tag::whereIn('id', $domaines_ids)->get();
            $profile->syncTagsWithType($domaines, 'domaine');
        }

        if ($request->has('skills')) {
            $skills_ids = collect($request->input('skills'))->pluck('id');
            $skills = Tag::whereIn('id', $skills_ids)->get();
            $profile->syncTagsWithType($skills, 'competence');
        }

        // Hack pour éviter de le mettre append -> trop gourmand en queries
        $profile['roles'] = $profile->roles;
        $profile['domaines'] = $profile->domaines;
        $profile['skills'] = $profile->skills;
        $profile['participations'] = $profile->participations;

        if ($profile->isResponsable()) {
            $profile['structures'] = $profile->structures;
        }

        return $profile;
    }

    public function upload(ProfileUpdateRequest $request, Profile $profile)
    {

        // Delete previous file
        if ($media = $profile->getFirstMedia('profiles')) {
            $media->delete();
        }

        $data = $request->all();
        $extension = $request->file('image')->guessExtension();
        $name = Str::random(30);

        $cropSettings = json_decode($data['cropSettings']);
        if (!empty($cropSettings)) {
            $stringCropSettings = implode(",", [
                $cropSettings->width,
                $cropSettings->height,
                $cropSettings->x,
                $cropSettings->y
            ]);
        } else {
            $pathName = $request->file('image')->getPathname();
            $infos = getimagesize($pathName);
            $stringCropSettings = implode(",", [
                $infos[0],
                $infos[1],
                0,
                0
            ]);
        }

        $profile
            ->addMedia($request->file('image'))
            ->usingName($name)
            ->usingFileName($name . '.' . $extension)
            ->withManipulations([
                'thumb' => ['manualCrop' => $stringCropSettings]
            ])
            ->toMediaCollection('profiles');

        return $profile;
    }

    public function uploadDelete(ProfileUpdateRequest $request, Profile $profile)
    {
        if ($media = $profile->getFirstMedia('profiles')) {
            $media->delete();
        }
    }

    public function firstname(Request $request)
    {
        $profile = Profile::where('email', 'ILIKE', request('email'))->first();

        if (!$profile) {
            return null;
        }

        return collect($profile)->only('first_name', 'email');
    }
}
