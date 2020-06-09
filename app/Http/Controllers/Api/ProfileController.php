<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Requests\Api\ProfileUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\ProfileCreateRequest;
use App\Notifications\ProfileInvitationSent;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfilesExport;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Http\Requests\ProfileRequest;
use App\Models\Participation;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Profile::role($request->header('Context-Role'))->with('structures'))
            ->allowedAppends('roles', 'has_user')
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                'referent_department'
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'))
        ;
    }

    public function participations(Request $request, Profile $profile)
    {
        return QueryBuilder::for(Participation::with(['mission.tuteur', 'mission.structure']))
        ->where('profile_id', $profile->id)
        ->defaultSort('-updated_at')
        ->paginate(config('query-builder.results_per_page'));
    }

    public function export(Request $request)
    {
        return Excel::download(new ProfilesExport($request), 'profiles.xlsx');
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
        return Profile::with(['structures:id,name'])->find($profile->id)
            ?: Profile::with(['structures:id,name'])->find($request->user()->profile->id);
    }

    public function update(ProfileUpdateRequest $request, Profile $profile = null)
    {
        $profile->update($request->validated());

        if ($request->has('domaines')) {
            $profile->syncTagsWithType($request->input('domaines'), 'domaine');
        }

        if ($request->has('skills')) {
            $profile->syncTagsWithType($request->input('skills'), 'competence');
        }

        // Hack pour éviter de le mettre append -> trop gourmand en queries
        $profile['roles'] = $profile->roles;
        $profile['domaines'] = $profile->domaines;
        $profile['skills'] = $profile->skills;

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
