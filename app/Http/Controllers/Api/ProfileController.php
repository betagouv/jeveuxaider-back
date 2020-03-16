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
use Spatie\QueryBuilder\AllowedFilter;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Profile::role($request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                'referent_department'
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'))
            ;
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
        return Profile::with('structures:id,name')->find($profile->id)
            ?: Profile::with('structures:id,name')->find($request->user()->profile->id);
    }

    public function update(ProfileUpdateRequest $request, Profile $profile = null)
    {
        $profile = $profile ?: $request->user()->profile;

        if (!$request->validated()) {
            return $request->validated();
        }

        $profile->update($request->validated());

        return $profile;
    }
}
