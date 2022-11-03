<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersTags;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Jobs\SendinblueSyncUser;
use App\Models\Profile;
use App\Sorts\ProfileParticipationsValidatedCountSort;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Profile::role($request->header('Context-Role')))
            ->allowedIncludes([
                'user',
                'participationsValidatedCount',
                'avatar',
                'tags',
            ])
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::scope('user.role'),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('zip'),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations),
                AllowedFilter::custom('tags', new FiltersTags)
            )
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                AllowedSort::custom('participations_validated_count', new ProfileParticipationsValidatedCountSort()),
            ])
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(ProfileRequest $request, Profile $profile)
    {
        return $profile->load(['user', 'user.territoires', 'user.structures', 'user.regionsAsReferent', 'user.departmentsAsReferent', 'user.reseaux', 'skills', 'domaines', 'avatar', 'activities', 'tags'])->loadCount(['participations', 'participationsValidated']);
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
            $skills = collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'profile_skills'];
            });
            $profile->skills()->sync($values);
        }

        if ($request->has('activities')) {
            $activities = collect($request->input('activities'));
            $values = $activities->pluck($activities, 'id')->toArray();
            $profile->activities()->sync(array_keys($values));
            if (config('services.sendinblue.sync')) {
                if ($profile->user) {
                    SendinblueSyncUser::dispatch($profile->user);
                }
            }
        }

        if ($request->has('tags')) {
            $tags = collect($request->input('tags'));
            $values = $tags->pluck($tags, 'id')->map(function ($item) {
                return ['field' => 'tags'];
            });
            $profile->tags()->sync($values);
        }

        return $profile;
    }

    public function firstname(Request $request)
    {
        $profile = Profile::where('email', 'ILIKE', request('email'))->first();

        if (! $profile) {
            return null;
        }

        return collect($profile)->only('first_name', 'email');
    }
}
