<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Requests\Api\ProfileUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\ProfileCreateRequest;
use App\Notifications\ProfileInvitationSent;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfilesExport;
use App\Exports\ProfilesReferentsDepartementsExport;
use App\Exports\ProfilesReferentsRegionsExport;
use App\Exports\ProfilesResponsablesExport;
use App\Filters\FiltersProfileCollectivity;
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
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // TODO : Mettre dans ProfileRequest ?
        if ($request->header('Context-Role') == 'responsable') {
            if (!request('filter')['match_mission']) {
                abort(403, "Vous n'êtes pas autorisé à accéder à ce contenu");
            }
            $missions = Mission::role('responsable')->available()->hasPlacesLeft()->get();
            if (!$missions->contains(request('filter')['match_mission'])) {
                abort(403, "Vous n'êtes pas autorisé à accéder à ce contenu");
            }
        }
        return QueryBuilder::for(Profile::role($request->header('Context-Role'))->with(['structures:name,id']))
            ->allowedAppends('roles', 'has_user', 'skills', 'domaines', 'referent_waiting_actions', 'referent_region_waiting_actions', 'responsable_waiting_actions')
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('postal_code', new FiltersProfilePostalCode),
                AllowedFilter::custom('zips', new FiltersProfileZips),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::custom('domaines', new FiltersProfileTag),
                AllowedFilter::custom('collectivity', new FiltersProfileCollectivity),
                AllowedFilter::custom('department', new FiltersProfileDepartment),
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                AllowedFilter::custom('skills', new FiltersProfileSkill),
                AllowedFilter::custom('match_mission', new FiltersMatchMission),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations),
                AllowedFilter::exact('referent_department'),
                'referent_region'
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'))
        ;
    }

    public function participations(Request $request, Profile $profile)
    {
        return QueryBuilder::for(Participation::with(['mission.responsable', 'mission.structure']))
            ->where('profile_id', $profile->id)
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    // LARAVEL EXCEL
    public function export(Request $request)
    {
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

    // FAST EXCEL
    // public function export(Request $request)
    // {
    //     $folder = 'public/'. config('app.env').'/exports/'.$request->user()->id . '/';
    //     $fileName = 'profiles-' . Str::random(8) . '.csv';
    //     $filePath = $folder . $fileName;

    //     $query = QueryBuilder::for(Profile::role($request->header('Context-Role')))
    //         ->allowedFilters(
    //             AllowedFilter::custom('search', new FiltersProfileSearch),
    //             AllowedFilter::custom('postal_code', new FiltersProfilePostalCode),
    //             AllowedFilter::custom('zips', new FiltersProfileZips),
    //             AllowedFilter::custom('role', new FiltersProfileRole),
    //             AllowedFilter::custom('domaines', new FiltersProfileTag),
    //             AllowedFilter::custom('collectivity', new FiltersProfileCollectivity),
    //             AllowedFilter::custom('disponibilities', new FiltersDisponibility),
    //             AllowedFilter::custom('skills', new FiltersProfileSkill),
    //             AllowedFilter::custom('match_mission', new FiltersMatchMission),
    //             AllowedFilter::exact('is_visible'),
    //             AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations),
    //             AllowedFilter::exact('referent_department'),
    //             'referent_region'
    //         )
    //         ->defaultSort('-created_at')
    //         ->get();

    //     $exportFilePath = (new FastExcel($query))
    //     ->export($fileName, function ($profile) {
    //         return [
    //             'id' => $profile->id,
    //             // 'user_id' => $profile->user_id,
    //             // 'first_name' => $profile->first_name,
    //             // 'last_name' => $profile->last_name,
    //             // 'email' => $profile->email,
    //             // 'phone' => $profile->phone,
    //             // 'mobile' => $profile->mobile,
    //             // 'zip' => $profile->zip,
    //             // 'referent_department' => $profile->referent_department,
    //             // 'referent_region' => $profile->referent_region,
    //             // 'reseau_id' => $profile->reseau_id,
    //             // 'service_civique' => $profile->service_civique,
    //             // 'is_visible' => $profile->is_visible,
    //             // 'created_at' => $profile->created_at,
    //             // 'updated_at' => $profile->updated_at,
    //         ];
    //     });

    //     return response()->json(['message'=> 'Export en cours...'], 200);
    // }

    public function exportReferentsDepartements(Request $request)
    {
        return Excel::download(new ProfilesReferentsDepartementsExport(), 'referents-departements.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportReferentsRegions(Request $request)
    {
        return Excel::download(new ProfilesReferentsRegionsExport(), 'referents-regions.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportResponsables(Request $request)
    {
        return Excel::download(new ProfilesResponsablesExport(), 'responsables.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function store(ProfileCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $user = $request->user();
        $profile = Profile::create($request->validated());

        $profile->notify(new ProfileInvitationSent($user, request('role')));

        return $profile;
    }

    public function show(ProfileRequest $request, Profile $profile = null)
    {
        return Profile::with(['structures:id,name'])->find($profile->id)->append('roles', 'has_user', 'skills', 'domaines')
            ?: Profile::with(['structures:id,name'])->find($request->user()->profile->id)->append('roles', 'has_user', 'skills', 'domaines');
    }

    public function update(ProfileUpdateRequest $request, Profile $profile = null)
    {
        $profile->update($request->validated());
        $domaines = $request->input('domaines');
        $skills = $request->input('skills');

        if ($domaines) {
            if (is_string($domaines[0])) {
                $profile->syncTagsWithType($domaines, 'domaine');
            }
        }

        if ($skills) {
            if (is_string($skills[0])) {
                $profile->syncTagsWithType($skills, 'competence');
            }
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
}
