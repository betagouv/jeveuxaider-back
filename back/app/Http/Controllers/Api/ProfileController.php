<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersParticipationSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Requests\Api\ProfileUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileMinParticipations;
use App\Http\Requests\ProfileRequest;
use App\Models\Participation;
use App\Models\Tag;
use Spatie\QueryBuilder\AllowedFilter;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Profile::role($request->header('Context-Role')))
            ->allowedIncludes([
                'user',
                'participationsValidatedCount',
                'avatar',
            ])
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                'department',
                'zip',
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations)
                // AllowedFilter::custom('zips', new FiltersProfileZips),
                // AllowedFilter::custom('domaines', new FiltersProfileTag),
                // AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                // AllowedFilter::custom('skills', new FiltersProfileSkill),
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    // public function statistics(Request $request, Profile $profile)
    // {
    //     return [
    //         'participations' => [
    //             'Toutes' => Participation::where('profile_id', $profile->id)->count(),
    //             'En attente de validation' => Participation::where('profile_id', $profile->id)->where('state', 'En attente de validation')->count(),
    //             'En cours de traitement' => Participation::where('profile_id', $profile->id)->where('state', 'En cours de traitement')->count(),
    //             'Validée' => Participation::where('profile_id', $profile->id)->where('state', 'Validée')->count(),
    //             'Refusée' => Participation::where('profile_id', $profile->id)->where('state', 'Refusée')->count(),
    //             'Annulée' => Participation::where('profile_id', $profile->id)->where('state', 'Annulée')->count(),
    //         ]
    //     ];
    // }

    // public function export(Request $request)
    // {

    //     $currentUser = User::find(Auth::guard('api')->user()->id);

    //     if(!$currentUser->profile->can_export_profiles){
    //         return response()->json(['message'=> 'Seuls les référents accrédités peuvent générer cet export'], 401);
    //     }

    //     $folder = 'public/'. config('app.env').'/exports/'.$request->user()->id . '/';
    //     $fileName = 'profiles-' . Str::random(8) . '.csv';
    //     $filePath = $folder . $fileName;

    //     (new ProfilesExport($request->header('Context-Role')))
    //         ->queue($filePath, 's3')
    //         ->chain([
    //             new NotifyUserOfCompletedExport($request->user(), $filePath),
    //         ]);

    //     return response()->json(['message'=> 'Export en cours...'], 200);
    // }

    // public function exportReferentsDepartements(Request $request)
    // {
    //     return Excel::download(new ProfilesReferentsDepartementsExport(), 'referents-departements.csv', \Maatwebsite\Excel\Excel::CSV);
    // }

    // public function exportReferentsRegions(Request $request)
    // {
    //     return Excel::download(new ProfilesReferentsRegionsExport(), 'referents-regions.csv', \Maatwebsite\Excel\Excel::CSV);
    // }

    // public function exportTetesDeReseau(Request $request)
    // {
    //     return Excel::download(new ProfilesTetesDeReseauExport(), 'tetes-de-reseau.csv', \Maatwebsite\Excel\Excel::CSV);
    // }

    // public function exportResponsables(Request $request)
    // {
    //     return Excel::download(new ProfilesResponsablesExport(), 'responsables.csv', \Maatwebsite\Excel\Excel::CSV);
    // }

    public function show(ProfileRequest $request, Profile $profile)
    {
        return $profile->load(['user', 'territoires', 'structures', 'reseau', 'skills', 'domaines', 'avatar'])->loadCount(['participations', 'participationsValidated']);
    }

    public function update(ProfileUpdateRequest $request, Profile $profile = null)
    {
        $profile->update($request->validated());

        if ($request->has('domaines')) {
            $domaines = collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'profile_domaines'];
            });
            $profile->domaines()->sync($values);
        }

        if ($request->has('skills')) {
            $skills =  collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'profile_skills'];
            });
            $profile->skills()->sync($values);
        }

        return $profile;
    }

    // public function upload(ProfileUpdateRequest $request, Profile $profile)
    // {

    //     // Delete previous file
    //     if ($media = $profile->getFirstMedia('profiles')) {
    //         $media->delete();
    //     }

    //     $data = $request->all();
    //     $extension = $request->file('image')->guessExtension();
    //     $name = Str::random(30);

    //     $cropSettings = json_decode($data['cropSettings']);
    //     if (!empty($cropSettings)) {
    //         $stringCropSettings = implode(",", [
    //             $cropSettings->width,
    //             $cropSettings->height,
    //             $cropSettings->x,
    //             $cropSettings->y
    //         ]);
    //     } else {
    //         $pathName = $request->file('image')->getPathname();
    //         $infos = getimagesize($pathName);
    //         $stringCropSettings = implode(",", [
    //             $infos[0],
    //             $infos[1],
    //             0,
    //             0
    //         ]);
    //     }

    //     $profile
    //         ->addMedia($request->file('image'))
    //         ->usingName($name)
    //         ->usingFileName($name . '.' . $extension)
    //         ->withManipulations([
    //             'thumb' => ['manualCrop' => $stringCropSettings]
    //         ])
    //         ->toMediaCollection('profiles');

    //     return $profile;
    // }

    // public function uploadDelete(ProfileUpdateRequest $request, Profile $profile)
    // {
    //     if ($media = $profile->getFirstMedia('profiles')) {
    //         $media->delete();
    //     }
    // }

    // public function firstname(Request $request)
    // {
    //     $profile = Profile::where('email', 'ILIKE', request('email'))->first();

    //     if (!$profile) {
    //         return null;
    //     }

    //     return collect($profile)->only('first_name', 'email');
    // }
}
